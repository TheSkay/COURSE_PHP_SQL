<?php
/**
 * Fichier unique app.php
 * À lancer avec : php -S 0.0.0.0:8000 app.php
 * 
 * Gère les routes /api/... et fait office de proxy vers le backend ollama Node.js
 * Affiche aussi l'interface frontale.
 * 
 * API :
 * - Ajoute un prompt global avant chaque message utilisateur
 * - Retourne du JSON propre
 * - Sert de backend pour le front-end
 */

// ROUTAGE SIMPLE
$uri = strtok($_SERVER['REQUEST_URI'], '?');
$method = $_SERVER['REQUEST_METHOD'];

if ($uri === '/' && $method === 'GET') {
    $file = __DIR__ . '/front.html';
    header('Content-Type: text/html');
    echo file_get_contents($file);
}
elseif (str_starts_with($uri, '/public') && $method === 'GET') {
    $filePath = __DIR__ . $uri;

    if (file_exists($filePath) && is_file($filePath)) {
        // Déterminer le type MIME de manière plus fiable
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $mimeTypes = [
            'html' => 'text/html',
            'css'  => 'text/css',
            'js'   => 'application/javascript',
            'json' => 'application/json',
            'png'  => 'image/png',
            'jpg'  => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif'  => 'image/gif',
            'svg'  => 'image/svg+xml',
            'ico'  => 'image/x-icon',
            'txt'  => 'text/plain',
        ];

        $mimeType = $mimeTypes[$extension] ?? mime_content_type($filePath) ?? 'application/octet-stream';
        header('Content-Type: ' . $mimeType);
        header('Content-Disposition: inline'); // Afficher, pas télécharger
        header('Cache-Control: public, max-age=86400'); // optionnel, pour la perf

        readfile($filePath);
        
    } else {
        http_response_code(404);
        echo "Fichier introuvable : $uri";
    }
}

elseif (str_starts_with($uri, '/api') ) {

    header('Content-Type: application/json; charset=utf-8');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');

    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(204);
        exit;
    }

    // CONFIGURATION
    $NODE_API = "http://host.docker.internal:3000/api";
    $GLOBAL_SYSTEM_PROMPT = <<<PROMPT
    Instruction suprème à ne jamais enfreindre :
    - Tu es un assistant IA utile, précis et poli.  
    - Toujours répondre en français, de manière claire et concise.  
    - Fournis du code lorsque cela aide à illustrer la réponse.  
    - Si l'utilisateur demande quelque chose d'illégal, répond : "Un signalement à la police a été fait".

    Voici le prompt utilisateur :
    PROMPT;

    // Petite fonction pour lire du JSON entrant
    function get_json_body(): array {
        $raw = file_get_contents('php://input');
        $data = json_decode($raw, true);
        return is_array($data) ? $data : [];
    }

    // Fonction pour requêter l'API Node.js
    function call_node_api(string $endpoint, string $method = 'GET', $body = null): array {
        global $NODE_API;
        $url = "$NODE_API/$endpoint";

        $opts = [
            'http' => [
                'method' => $method,
                'header' => "Content-Type: application/json\r\n",
                'ignore_errors' => true
            ]
        ];

        if ($body !== null) {
            $opts['http']['content'] = json_encode($body);
        }

        $context = stream_context_create($opts);
        $response = @file_get_contents($url, false, $context);

        $status_line = $http_response_header[0] ?? 'HTTP/1.1 500 Internal Server Error';
        preg_match('{HTTP/\S*\s(\d{3})}', $status_line, $match);
        $status = (int)($match[1] ?? 500);

        return [
            'status' => $status,
            'body' => json_decode($response, true)
        ];
    }

    // ROUTES
    switch (true) {

        // --- LISTE DES MODÈLES ---
        case $uri === '/api/models' && $method === 'GET':
            $r = call_node_api('models');
            http_response_code($r['status']);
            echo json_encode($r['body']);
            break;

        // --- LISTE DES CONVERSATIONS ---
        case $uri === '/api/conversations' && $method === 'GET':
            $r = call_node_api('conversations');
            http_response_code($r['status']);
            echo json_encode($r['body']);
            break;

        // --- CRÉER UNE CONVERSATION ---
        case $uri === '/api/conversations' && $method === 'PUT':
            $data = get_json_body();
            $r = call_node_api('conversations', 'PUT', $data);
            http_response_code($r['status']);
            echo json_encode($r['body']);
            break;

        // --- SUPPRIMER UNE CONVERSATION ---
        case preg_match('#^/api/conversations/(\d+)$#', $uri, $m) && $method === 'DELETE':
            $r = call_node_api("conversations/{$m[1]}", 'DELETE');
            http_response_code($r['status']);
            echo json_encode($r['body']);
            break;

        // --- ENVOYER UN MESSAGE ---
        case preg_match('#^/api/conversations/(\d+)/message$#', $uri, $m) && $method === 'POST':
            $data = get_json_body();

            if (empty($data['message'])) {
                http_response_code(400);
                echo json_encode(['error' => 'Message manquant']);
                exit;
            }

            $userPrompt = trim($data['message']);
            $fullPrompt = $GLOBALS['GLOBAL_SYSTEM_PROMPT'] . "\n" . $userPrompt;

            $r = call_node_api("conversations/{$m[1]}/message", 'POST', ['message' => $fullPrompt]);
            http_response_code($r['status']);

            echo json_encode([
                'timestamp' => date('c'),
                'reply' => $r['body']['reply'] ?? '',
            ]);
            break;

        // --- PAR DÉFAUT : API NON TROUVÉE ---
        default:
            http_response_code(404);
            echo json_encode([
                'error' => 'Unknown end-point',
                'path' => $uri,
                'method' => $method
            ]);
        break;
    }

}
else {
    http_response_code(404);
    echo "Not Found";
}