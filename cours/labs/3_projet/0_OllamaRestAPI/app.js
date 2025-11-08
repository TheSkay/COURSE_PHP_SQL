const express = require('express');
const { exec } = require('child_process');
const pty = require('node-pty');
const app = express();
app.use(express.json());

const MAX_CONVERSATIONS = 5;
let nextConversationId = 0;

// Stocke les conversations persistantes
// Format : { id, process, buffer, busy, model }
const conversations = [];

function getConversation(id) {
    return conversations.find(c => c.id === id);
}

async function sleep( n ) {
    return new Promise( (resolve) => setTimeout( resolve, n ))
}

function cleanText(input) {

    // Supprime les séquences ANSI (couleurs, curseur, etc.)
    let text = input.replace(/\u001b\[[0-9;?]*[a-zA-Z]/g, '');

    // Remplace les retours chariot + saut de ligne par un simple saut de ligne
    text = text.replace(/\r\n/g, '\n');

    // Supprime le prompt final de Ollama
    text = text.replace(/>>> Send a message.*$/s, '').trim();
    
    return text;
}

// Endpoint pour lister les modèles Ollama existants
app.get('/api/models', (_, res) => {
    console.log(`--> Display models`);
    exec('ollama list', (error, stdout, stderr) => {
        if (error) {
            console.error('Error listing models:', stderr);
            return res.status(500).json({ error: stderr });
        }

        const lines = stdout.split('\n').map(l => l.trim()).filter(l => l.length > 0);
        const result = [];
        for (let i = 1; i < lines.length; i++) {
            const line = lines[i];
            const parts = line.split(/\s{2,}/);
            if (parts.length >= 4) {
                const [name, id, size, modified] = parts;
                result.push({ name, id, size, modified });
            }
        }
        res.json(result);
    });
});

// Endpoint pour créer une conversation avec choix de modèle
app.put('/api/conversations', (req, res) => {
    const { model } = req.body;
    if (!model) return res.status(400).json({ error: 'Model is required' });

    if (conversations.length >= MAX_CONVERSATIONS) {
        return res.status(400).json({ error: 'Max conversations reached' });
    }

    const id = nextConversationId++;
    console.log(`--> Create new chat #${id}`);

    // Utilisation de node-pty pour un pseudo-terminal interactif
    const proc = pty.spawn('ollama', ['run', model], {
        name: 'xterm-color',
        cols: 80,
        rows: 30
    });

    const conv = { id, process: proc, buffer: '', busy: false, model };
    conversations.push(conv);

    // Écoute globale pour debug (optionnel)
    proc.onData( data  => {
        conv.buffer += data;
        if( conv.buffer.trim().includes( '/? for help' ) ){
            conv.busy = false;
            conv.buffer = cleanText(conv.buffer);
        }
    });

    res.status(201).json({ id, model });
});

// Endpoint pour lister les conversations existantes
app.get('/api/conversations', (req, res) => {
    const list = conversations.map(c => ({ id: c.id, busy: c.busy, model: c.model }));
    console.log(`--> List availables chats (count=${list.length})`);
    res.json(list);
});

// Endpoint pour supprimer une conversation
app.delete('/api/conversations/:id', (req, res) => {
    const id = parseInt(req.params.id);
    console.log(`--> Remove chat #${id}`);
    const index = conversations.findIndex(c => c.id === id);
    if (index === -1) return res.status(404).json({ error: 'Conversation not found' });

    const conv = conversations[index];
    conv.process.kill();
    conversations.splice(index, 1);
    res.json({ success: true });
});

// Endpoint pour envoyer un message et récupérer la réponse
app.post('/api/conversations/:id/message', async (req, res) => {
    const id = parseInt(req.params.id);
    const conv = getConversation(id);
    if (!conv) return res.status(404).json({ error: 'Conversation not found' });

    console.log(`--> Talk to #${id}`);

    const { message } = req.body;
    if (!message) return res.status(400).json({ error: 'Message required' });
    if (conv.busy) return res.status(429).json({ error: 'Conversation busy' });

    conv.busy = true;
    conv.buffer = '';
    conv.canSend = true ;

    // Envoi du message au modèle
    conv.process.write(message + "\n");
    process.stdout.write('--> Modèle en cours de raisonnement.');

    do {
        await sleep(100);
        process.stdout.write('.');
    } while( conv.busy );

    console.log('OK')

    res.json({ reply: cleanText(conv.buffer) });
});

// Démarrage du serveur
app.listen(3000, () => {
    console.log('Ollama REST API running on http://localhost:3000');
});

process.stdin.resume();