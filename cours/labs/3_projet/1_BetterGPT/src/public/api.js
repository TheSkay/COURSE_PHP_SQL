async function callApi(endpoint, method = 'GET', data = null) {
    let headers = new Headers();
    headers.append('Content-Type', 'application/json; charset=utf-8');

    const options = {
        method,
        headers,
    };

    if( data !== null ){
        options.body = JSON.stringify(data);
    }

    try {
        const response = await fetch(`http://localhost:8000/api/${endpoint}`, options);
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return await response.json();
    } catch (error) {
        console.error('There has been a problem with the fetch operation:', error);
        throw error;
    }
}

// Fonction pour récupérer la liste des modèles
async function getModels() {
    try {
        return callApi('models');
    } catch (error) {
        console.error('Error fetching models:', error);
        return [];
    }
}

// Fonction pour récupérer les conversations ouvertes
async function getConversations() {
    try {
        return callApi(`conversations`);
    } catch (error) {
        console.error('Error fetching conversations:', error);
        return [];
    }
}

// Fonction pour créer une nouvelle conversation
async function createConversation(model) {
    const data = {
        model
    };

    try {
        await callApi('conversations', 'PUT', data);
        return true ;
    } catch (error) {
        console.error('Error creating conversation:', model, error);
        return false;
    }
}

// Fonction pour supprimer une conversation
async function deleteConversation(conversationId) {
    try {
        await callApi(`conversations/${conversationId}`, 'DELETE');
        return true ;
    } catch (error) {
        console.error('Error deleting conversation:', error);
        return false;
    }
}

// Fonction pour envoyer un message à la conversation
async function sendMessage(conversationId, userMessage) {
    const data = {
        message: userMessage
    };

    try {
        const response = await callApi(`conversations/${conversationId}/message`, 'POST', data);
        return response.reply;
    } catch (error) {
        console.error('Error sending message:', error);
        return null;
    }
}