window.onload = () => {

    // --- Hydratation des modèles depuis l’API ---
    async function hydrateModels() {
        try {
            console.log(`Hydratation des modèles...`);
            const models = await getModels();
            const wrapperModelsList = document.querySelector('#model-select');
            wrapperModelsList.innerHTML = '';

            models.forEach(({ name, size }) => {
                const el = document.createElement('option');
                el.value = name;
                el.textContent = `${name} (${size})`;
                wrapperModelsList.appendChild(el);
            });
        } catch (error) {
            console.error(`Erreur lors de l'hydratation des modèles :`, error);
            setTimeout(hydrateModels, 2000);
        }
    }

    // --- Sélecteurs DOM ---
    const chatList = document.getElementById('chat-list');
    const chatHeader = document.getElementById('chat-header');
    const messages = document.getElementById('messages');
    const input = document.getElementById('message-input');
    const sendBtn = document.getElementById('send-btn');
    const modal = document.getElementById('new-chat-modal');
    const newChatBtn = document.getElementById('new-chat-btn');
    const closeModal = document.getElementById('close-modal');
    const startChatBtn = document.getElementById('start-chat-btn');
    const modelSelect = document.getElementById('model-select');

    // --- État du front ---
    let chatMap = {};         // { [conversationId]: { name, model, messages: [] } }
    let currentChatId = null; // Conversation sélectionnée

    // --- Afficher les messages ---
    function renderMessages(chatId) {
        messages.innerHTML = "";
        const chat = chatMap[chatId];
        if (!chat || !chat.messages) return;

        chat.messages.forEach(msg => {
            const div = document.createElement('div');
            div.classList.add('message', msg.role);

            if (msg.role === 'bot') {
                // Affiche le markdown avec mise en forme
                div.innerHTML = marked.parse(msg.text || "");
            } else {
                // Messages utilisateur -> texte brut (pas de markdown)
                div.textContent = msg.text;
            }

            messages.appendChild(div);
        });

        messages.scrollTop = messages.scrollHeight;
    }


    // --- Sélection d’une conversation ---
    function setActiveChat(chatId) {
        if (!chatMap[chatId]) return;
        currentChatId = chatId;

        document.querySelectorAll('.chat-item').forEach(el => el.classList.remove('active'));
        const item = document.querySelector(`.chat-item[data-id="${chatId}"]`);
        if (item) item.classList.add('active');

        chatHeader.textContent = chatMap[chatId].name;
        renderMessages(chatId);
    }

    // --- Ajouter une conversation dans la liste ---
    function addChatToList(chatId, chatName) {
        if (document.querySelector(`.chat-item[data-id="${chatId}"]`)) return; // évite doublon

        const el = document.createElement('div');
        el.classList.add('chat-item');
        el.dataset.id = chatId;

        const span = document.createElement('span');
        span.classList.add('chat-name');
        span.textContent = chatName;

        const del = document.createElement('button');
        del.classList.add('delete-chat-btn');
        del.innerHTML = '&times;';
        del.addEventListener('click', async e => {
            e.stopPropagation();
            if (confirm('Supprimer cette conversation ?')) {
                try {
                    await deleteConversation(chatId);
                    delete chatMap[chatId];
                    el.remove();
                    if (currentChatId === chatId) {
                        messages.innerHTML = "";
                        chatHeader.textContent = "";
                        currentChatId = null;
                    }
                } catch (error) {
                    console.error('Erreur lors de la suppression :', error);
                }
            }
        });

        el.appendChild(span);
        el.appendChild(del);
        el.addEventListener('click', () => setActiveChat(chatId));
        chatList.appendChild(el);
    }

    // --- Charger toutes les conversations depuis le backend ---
    async function loadConversations() {
        try {
            console.log('Chargement des conversations...');
            const conversations = await getConversations();

            // On ne vide plus chatMap — on le met à jour sans perdre les messages locaux
            for (const conv of conversations) {
                if (!chatMap[conv.id]) {
                    chatMap[conv.id] = {
                        id: conv.id,
                        name: conv.name || `Conversation ${conv.id}`,
                        model: conv.model || null,
                        messages: conv.messages || [] // si le backend les fournit
                    };
                    addChatToList(conv.id, chatMap[conv.id].name);
                } else {
                    // On garde les messages existants et met à jour nom/modèle
                    chatMap[conv.id].name = conv.name || chatMap[conv.id].name;
                    chatMap[conv.id].model = conv.model || chatMap[conv.id].model;
                }
            }

            // S’il n’y a encore aucune conversation active
            if (!currentChatId && conversations.length > 0) {
                setActiveChat(conversations[0].id);
            }

        } catch (error) {
            console.error('Erreur lors du chargement des conversations :', error);
        }
    }

    // --- Créer une nouvelle conversation ---
    startChatBtn.addEventListener('click', async () => {
        const model = modelSelect.value;
        if (!model) return alert('Veuillez sélectionner un modèle');

        try {
            const ok = await createConversation(model);
            if (ok) {
                console.log('Nouvelle conversation créée');
                await loadConversations(); // Recharge la liste depuis l’API
            } else {
                alert('Erreur : impossible de créer la conversation');
            }
        } catch (error) {
            console.error('Erreur création conversation :', error);
        } finally {
            modal.style.display = 'none';
        }
    });

    // --- Envoyer un message ---
    async function handleSendMessage() {
        const text = input.value.trim();
        if (!text || !currentChatId) return;

        const chat = chatMap[currentChatId];
        if (!chat.messages) chat.messages = [];

        // Ajouter localement le message utilisateur
        chat.messages.push({ role: "user", text });
        renderMessages(currentChatId);
        input.value = "";

        try {
            const reply = await sendMessage(currentChatId, text);

            // Ajouter la réponse du bot localement
            if (reply) {
                chat.messages.push({ role: "bot", text: reply });
                renderMessages(currentChatId);
            }
        } catch (error) {
            console.error("Erreur lors de l'envoi du message :", error);
        }
    }

    sendBtn.addEventListener('click', handleSendMessage);
    input.addEventListener('keypress', e => {
        if (e.key === 'Enter') handleSendMessage();
    });

    // --- Gestion modale ---
    newChatBtn.addEventListener('click', () => modal.style.display = 'flex');
    closeModal.addEventListener('click', () => modal.style.display = 'none');
    window.addEventListener('click', e => { if (e.target === modal) modal.style.display = 'none'; });

    // --- Initialisation ---
    hydrateModels();
    loadConversations();
};
