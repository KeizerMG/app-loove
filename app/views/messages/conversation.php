<?php require APPROOT . '/views/includes/header.php'; ?>

<style>
    .messages-container {
        display: flex;
        background-color: var(--color-surface);
        border-radius: var(--border-radius-lg);
        box-shadow: var(--shadow-md);
        overflow: hidden;
        height: calc(100vh - 15rem);
        min-height: 500px;
    }
    
    .conversations-list {
        width: 300px;
        border-right: 1px solid var(--color-divider);
        overflow-y: auto;
        background-color: var(--color-surface-variant);
    }
    
    .conversation-item {
        display: flex;
        align-items: center;
        padding: 1rem;
        border-bottom: 1px solid var(--color-divider);
        cursor: pointer;
        transition: var(--transition-normal);
    }
    
    .conversation-item:hover, .conversation-item.active {
        background-color: var(--color-primary-soft);
    }
    
    .conversation-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
        margin-right: 1rem;
    }
    
    .conversation-info {
        flex: 1;
        min-width: 0;
    }
    
    .conversation-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.25rem;
    }
    
    .conversation-name {
        font-weight: 600;
        color: var(--color-text-primary);
        margin: 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .conversation-time {
        font-size: 0.8rem;
        color: var(--color-text-tertiary);
        white-space: nowrap;
    }
    
    .conversation-last-message {
        font-size: 0.9rem;
        color: var(--color-text-secondary);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .unread-badge {
        background-color: var(--color-primary);
        color: white;
        border-radius: 50%;
        font-size: 0.7rem;
        padding: 0.25rem 0.5rem;
        margin-left: 0.5rem;
    }
    
    .chat-container {
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    
    .chat-header {
        display: flex;
        align-items: center;
        padding: 1rem;
        border-bottom: 1px solid var(--color-divider);
        background-color: var(--color-surface);
    }
    
    .chat-header-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        margin-right: 1rem;
    }
    
    .chat-header-name {
        font-weight: 600;
        margin: 0;
    }
    
    .chat-header-info {
        font-size: 0.9rem;
        color: var(--color-text-secondary);
        margin: 0;
    }
    
    .chat-messages {
        flex: 1;
        padding: 1rem;
        overflow-y: auto;
        background-color: var(--color-surface-variant);
    }
    
    .message {
        display: flex;
        margin-bottom: 1rem;
        max-width: 70%;
    }
    
    .message.outgoing {
        margin-left: auto;
        flex-direction: row-reverse;
    }
    
    .message-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        object-fit: cover;
        margin-right: 0.75rem;
    }
    
    .message.outgoing .message-avatar {
        margin-right: 0;
        margin-left: 0.75rem;
    }
    
    .message-content {
        background-color: var(--color-surface);
        padding: 0.75rem 1rem;
        border-radius: var(--border-radius-md);
        box-shadow: var(--shadow-sm);
    }
    
    .message.outgoing .message-content {
        background-color: var(--color-primary);
        color: white;
    }
    
    .message-text {
        margin: 0;
        word-break: break-word;
    }
    
    .message-time {
        font-size: 0.75rem;
        color: var(--color-text-tertiary);
        margin-top: 0.25rem;
        text-align: right;
    }
    
    .message.outgoing .message-time {
        color: rgba(255, 255, 255, 0.8);
    }
    
    .chat-input {
        display: flex;
        align-items: center;
        padding: 1rem;
        border-top: 1px solid var(--color-divider);
        background-color: var(--color-surface);
    }
    
    .chat-input-field {
        flex: 1;
        padding: 0.75rem 1rem;
        border: 1px solid var(--color-divider);
        border-radius: var(--border-radius-md);
        background-color: var(--color-surface-variant);
        margin-right: 0.75rem;
        font-family: var(--font-family);
        resize: none;
    }
    
    .chat-input-field:focus {
        outline: none;
        border-color: var(--color-primary);
    }
    
    .chat-send-btn {
        padding: 0.75rem;
        border-radius: 50%;
        background-color: var(--color-primary);
        color: white;
        border: none;
        cursor: pointer;
        transition: var(--transition-normal);
    }
    
    .chat-send-btn:hover {
        background-color: darken(var(--color-primary), 10%);
        transform: scale(1.05);
    }
    
    .day-divider {
        text-align: center;
        margin: 1.5rem 0;
        position: relative;
    }
    
    .day-divider::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 1px;
        background-color: var(--color-divider);
        z-index: 1;
    }
    
    .day-divider span {
        position: relative;
        background-color: var(--color-surface-variant);
        padding: 0 1rem;
        font-size: 0.85rem;
        color: var(--color-text-tertiary);
        z-index: 2;
    }
    
    @media (max-width: 768px) {
        .conversations-list {
            display: none;
        }
    }
</style>

<section class="messages-loove-section slide-up">
    <div style="margin-bottom: 1.5rem;">
        <h1 class="section-title-loove">Conversation avec <?php echo $data['other_user']->first_name; ?></h1>
    </div>

    <div class="messages-container">
        <!-- Liste des conversations (peut être masquée en mobile) -->
        <div class="conversations-list">
            <!-- Cette partie est généralement chargée dynamiquement mais pour simplifier, nous la cachons en mobile -->
            <a href="<?php echo BASEURL; ?>/messages" class="conversation-item" style="background-color: var(--color-primary-soft);">
                <i class="fas fa-chevron-left" style="margin-right: 0.5rem;"></i> Retour aux conversations
            </a>
        </div>
        
        <!-- Conteneur de chat -->
        <div class="chat-container">
            <!-- En-tête du chat -->
            <div class="chat-header">
                <img src="<?php echo (strpos($data['other_user']->profile_pic, 'https://') === 0) ? $data['other_user']->profile_pic : BASEURL . '/img/profiles/' . (!empty($data['other_user']->profile_pic) ? $data['other_user']->profile_pic : 'default.jpg'); ?>" alt="Photo de <?php echo $data['other_user']->first_name; ?>" class="chat-header-avatar">
                <div>
                    <h3 class="chat-header-name"><?php echo $data['other_user']->first_name; ?>, <?php echo isset($data['other_user']->age) ? $data['other_user']->age : '?'; ?></h3>
                    <p class="chat-header-info">
                        <?php echo isset($data['other_user']->location) ? $data['other_user']->location : 'Localisation non spécifiée'; ?>
                    </p>
                </div>
            </div>
            
            <!-- Messages -->
            <div class="chat-messages" id="chatMessages">
                <?php if(!empty($data['messages'])) : ?>
                    <?php 
                    $currentDay = '';
                    foreach($data['messages'] as $message) : 
                        $messageDay = date('Y-m-d', strtotime($message->created_at));
                        if($messageDay != $currentDay) :
                            $currentDay = $messageDay;
                            $dayText = '';
                            
                            $today = date('Y-m-d');
                            $yesterday = date('Y-m-d', strtotime('-1 day'));
                            
                            if($messageDay == $today) {
                                $dayText = 'Aujourd\'hui';
                            } elseif($messageDay == $yesterday) {
                                $dayText = 'Hier';
                            } else {
                                $dayText = date('d/m/Y', strtotime($messageDay));
                            }
                    ?>
                        <div class="day-divider">
                            <span><?php echo $dayText; ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <div class="message <?php echo ($message->sender_id == $_SESSION['user_id']) ? 'outgoing' : ''; ?>">
                        <img src="<?php echo (strpos($message->sender_profile_pic, 'https://') === 0) ? $message->sender_profile_pic : BASEURL . '/img/profiles/' . (!empty($message->sender_profile_pic) ? $message->sender_profile_pic : 'default.jpg'); ?>" alt="Photo de <?php echo $message->sender_first_name; ?>" class="message-avatar">
                        <div class="message-content">
                            <p class="message-text"><?php echo $message->message; ?></p>
                            <p class="message-time"><?php echo date('H:i', strtotime($message->created_at)); ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <div style="text-align: center; padding: 2rem; color: var(--color-text-tertiary);">
                        <p>Aucun message. Commencez la conversation !</p>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Formulaire d'envoi de message -->
            <div class="chat-input">
                <textarea id="messageInput" class="chat-input-field" placeholder="Écrivez votre message..." rows="1"></textarea>
                <button id="sendMessageBtn" class="chat-send-btn">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatMessages = document.getElementById('chatMessages');
    const messageInput = document.getElementById('messageInput');
    const sendMessageBtn = document.getElementById('sendMessageBtn');
    
    // Faire défiler vers le bas pour voir les derniers messages
    chatMessages.scrollTop = chatMessages.scrollHeight;
    
    // Gérer l'envoi de message
    function sendMessage() {
        const message = messageInput.value.trim();
        if(!message) return;
        
        // Désactiver le bouton pendant l'envoi
        sendMessageBtn.disabled = true;
        sendMessageBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        
        // Préparation des données
        const formData = new FormData();
        formData.append('receiver_id', <?php echo $data['other_user']->id; ?>);
        formData.append('message', message);
        
        // Envoyer le message via AJAX en utilisant Fetch API
        fetch('<?php echo BASEURL; ?>/messages/send', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: new URLSearchParams(formData)
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur réseau lors de l\'envoi du message');
            }
            return response.json();
        })
        .then(data => {
            // Réactiver le bouton
            sendMessageBtn.disabled = false;
            sendMessageBtn.innerHTML = '<i class="fas fa-paper-plane"></i>';
            
            if(data.success) {
                // Ajouter le message à la conversation
                addMessageToChat(data.message_data);
                
                // Vider le champ de message
                messageInput.value = '';
                messageInput.style.height = 'auto';
                
                // Faire défiler vers le bas
                chatMessages.scrollTop = chatMessages.scrollHeight;
                
                // Si c'est un utilisateur factice, afficher une réponse automatique après un délai
                if(data.auto_reply) {
                    const delay = data.auto_reply.delay * 1000; // Convertir en millisecondes
                    
                    // Afficher l'indicateur "en train d'écrire"
                    showTypingIndicator();
                    
                    setTimeout(() => {
                        // Cacher l'indicateur
                        hideTypingIndicator();
                        
                        // Ajouter la réponse
                        addMessageToChat(data.auto_reply.message_data);
                        
                        // Faire défiler vers le bas
                        chatMessages.scrollTop = chatMessages.scrollHeight;
                    }, delay);
                }
            } else {
                showErrorMessage(data.message || 'Une erreur est survenue lors de l\'envoi du message');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            // Réactiver le bouton
            sendMessageBtn.disabled = false;
            sendMessageBtn.innerHTML = '<i class="fas fa-paper-plane"></i>';
            showErrorMessage('Une erreur est survenue lors de l\'envoi du message. Veuillez réessayer.');
        });
    }
    
    // Fonction pour afficher un message d'erreur
    function showErrorMessage(message) {
        const errorElement = document.createElement('div');
        errorElement.className = 'error-message';
        errorElement.innerHTML = `
            <i class="fas fa-exclamation-circle"></i> ${message}
        `;
        
        // Ajouter au chat
        chatMessages.appendChild(errorElement);
        
        // Faire défiler vers le bas
        chatMessages.scrollTop = chatMessages.scrollHeight;
        
        // Supprimer après quelques secondes
        setTimeout(() => {
            errorElement.classList.add('fade-out');
            setTimeout(() => {
                errorElement.remove();
            }, 500);
        }, 5000);
    }
    
    // Fonction pour ajouter un message au chat
    function addMessageToChat(messageData) {
        if (!messageData) return;
        
        const messageElement = document.createElement('div');
        const isSentByMe = messageData.sender_id == <?php echo $_SESSION['user_id']; ?>;
        messageElement.className = `message ${isSentByMe ? 'outgoing' : ''}`;
        
        try {
            const now = new Date(messageData.created_at || Date.now());
            const hours = now.getHours().toString().padStart(2, '0');
            const minutes = now.getMinutes().toString().padStart(2, '0');
            
            const profilePic = messageData.sender_profile_pic && messageData.sender_profile_pic.includes('https://') ? 
                messageData.sender_profile_pic : 
                '<?php echo BASEURL; ?>/img/profiles/' + (messageData.sender_profile_pic || 'default.jpg');
            
            messageElement.innerHTML = `
                <img src="${profilePic}" alt="Photo de ${messageData.sender_first_name}" class="message-avatar" onerror="this.src='<?php echo BASEURL; ?>/img/profiles/default.jpg'">
                <div class="message-content">
                    <p class="message-text">${messageData.message}</p>
                    <p class="message-time">${hours}:${minutes}</p>
                </div>
            `;
            
            chatMessages.appendChild(messageElement);
        } catch (e) {
            console.error('Erreur lors de l\'ajout du message au chat:', e);
            showErrorMessage('Erreur lors de l\'affichage du message');
        }
    }
    
    // Fonction pour afficher l'indicateur "en train d'écrire"
    function showTypingIndicator() {
        // Vérifier si l'indicateur existe déjà
        if(document.getElementById('typingIndicator')) return;
        
        const indicatorElement = document.createElement('div');
        indicatorElement.id = 'typingIndicator';
        indicatorElement.className = 'message';
        
        const profilePic = `<?php echo (strpos($data['other_user']->profile_pic, 'https://') === 0) ? $data['other_user']->profile_pic : BASEURL . '/img/profiles/' . (!empty($data['other_user']->profile_pic) ? $data['other_user']->profile_pic : 'default.jpg'); ?>`;
        
        indicatorElement.innerHTML = `
            <img src="${profilePic}" alt="Photo de <?php echo $data['other_user']->first_name; ?>" class="message-avatar">
            <div class="message-content typing-indicator">
                <span></span>
                <span></span>
                <span></span>
            </div>
        `;
        
        chatMessages.appendChild(indicatorElement);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
    
    // Fonction pour cacher l'indicateur "en train d'écrire"
    function hideTypingIndicator() {
        const indicator = document.getElementById('typingIndicator');
        if(indicator) {
            indicator.remove();
        }
    }
    
    // Événement de clic sur le bouton d'envoi
    sendMessageBtn.addEventListener('click', sendMessage);
    
    // Événement d'appui sur Entrée dans le champ de message
    messageInput.addEventListener('keypress', function(e) {
        if(e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });
    
    // Ajuster automatiquement la hauteur du textarea
    messageInput.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });
});
</script>

<style>
    /* Styles pour l'indicateur "en train d'écrire" */
    .typing-indicator {
        background-color: var(--color-surface) !important;
        padding: 1rem !important;
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 65px;
    }
    
    .typing-indicator span {
        height: 8px;
        width: 8px;
        margin: 0 2px;
        background-color: var(--color-text-secondary);
        border-radius: 50%;
        display: inline-block;
        animation: typing-dot 1.5s infinite ease-in-out;
    }
    
    .typing-indicator span:nth-child(1) {
        animation-delay: 0s;
    }
    
    .typing-indicator span:nth-child(2) {
        animation-delay: 0.3s;
    }
    
    .typing-indicator span:nth-child(3) {
        animation-delay: 0.6s;
    }
    
    @keyframes typing-dot {
        0%, 60%, 100% {
            transform: translateY(0);
        }
        30% {
            transform: translateY(-5px);
        }
    }
    
    /* Style pour les messages d'erreur */
    .error-message {
        background-color: rgba(255, 69, 58, 0.1);
        color: #ff453a;
        padding: 0.75rem 1rem;
        margin: 1rem auto;
        border-radius: var(--border-radius-md);
        text-align: center;
        max-width: 80%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        transition: opacity 0.5s ease;
    }
    
    .error-message.fade-out {
        opacity: 0;
    }
    
    /* Style pour le bouton d'envoi désactivé */
    .chat-send-btn:disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }
</style>

<?php require APPROOT . '/views/includes/footer.php'; ?>
