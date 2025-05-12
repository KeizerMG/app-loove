<div class="container py-8">
    <div class="mb-4">
        <a href="<?= APP_URL ?>/messages" class="inline-flex items-center text-secondary hover:underline">
            <i class="fas fa-arrow-left mr-2"></i> Back to Messages
        </a>
    </div>
    
    <?php if(isset($_GET['error'])): ?>
        <div class="bg-danger bg-opacity-10 text-danger p-4 rounded-md mb-6">
            <?php
                $error = $_GET['error'];
                switch($error) {
                    case 'invalid_message':
                        echo 'Please enter a message to send.';
                        break;
                    case 'send_failed':
                        echo 'Failed to send message. Please try again.';
                        break;
                    default:
                        echo 'An error occurred.';
                }
            ?>
        </div>
    <?php endif; ?>
    
    <div class="chat-container">
        <!-- Chat sidebar with user info -->
        <div class="chat-sidebar">
            <div class="chat-user-info">
                <div class="chat-user-avatar">
                    <?php if(!empty($otherUser['profile_picture'])): ?>
                        <img src="<?= APP_URL . '/' . $otherUser['profile_picture'] ?>" 
                             alt="<?= htmlspecialchars($otherUser['first_name']) ?>" class="user-avatar">
                    <?php else: ?>
                        <div class="user-avatar-placeholder">
                            <?= strtoupper(substr($otherUser['first_name'], 0, 1)) ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="chat-user-details">
                    <h2 class="chat-user-name">
                        <?= htmlspecialchars($otherUser['first_name']) ?>,
                        <?= (new DateTime($otherUser['date_of_birth']))->diff(new DateTime())->y ?>
                    </h2>
                    
                    <?php if(!empty($otherUser['location'])): ?>
                        <p class="chat-user-location">
                            <i class="fas fa-map-marker-alt mr-1"></i>
                            <?= htmlspecialchars($otherUser['location']) ?>
                        </p>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="chat-user-actions">
                <a href="<?= APP_URL ?>/profile/view?id=<?= $otherUser['id'] ?>" class="btn btn-outline btn-sm w-full">
                    <i class="fas fa-user mr-2"></i> View Profile
                </a>
            </div>
        </div>
        
 
        <div class="chat-main">
     
            <div class="chat-messages" id="chat-messages">
                <?php if(empty($messages)): ?>
                    <div class="no-messages-placeholder">
                        <div class="placeholder-icon">💬</div>
                        <p class="placeholder-text">No messages yet. Say hello!</p>
                    </div>
                <?php else: ?>
                    <?php 
                    $prevDate = null;
                    foreach($messages as $message): 
                      
                        $msgDate = date("Y-m-d", strtotime($message['created_at']));
                        $displayDate = date("F j, Y", strtotime($message['created_at']));
                        
                        if ($prevDate !== $msgDate):
                    ?>
                        <div class="message-date-separator">
                            <span><?= $displayDate ?></span>
                        </div>
                    <?php 
                        endif; 
                        $prevDate = $msgDate;
                    ?>
                    
                    <div class="message <?= $message['message_type'] === 'sent' ? 'message-sent' : 'message-received' ?>">
                        <div class="message-bubble">
                            <?= nl2br(htmlspecialchars($message['message'])) ?>
                        </div>
                        <div class="message-time">
                            <?= date("g:i A", strtotime($message['created_at'])) ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <!-- Message input form -->
            <form action="<?= APP_URL ?>/messages/send" method="POST" class="chat-input-form">
                <input type="hidden" name="receiver_id" value="<?= $otherUser['id'] ?>">
                <div class="chat-input-container">
                    <textarea name="message" id="message-input" placeholder="Type a message..." class="chat-input" required></textarea>
                    <button type="submit" class="chat-send-btn">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.chat-container {
    display: flex;
    height: 70vh;
    min-height: 500px;
    background-color: white;
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-md);
    overflow: hidden;
}

.chat-sidebar {
    width: 280px;
    display: flex;
    flex-direction: column;
    border-right: 1px solid #f0f0f0;
    background-color: #f9f9f9;
}

.chat-user-info {
    padding: 1.5rem;
    border-bottom: 1px solid #f0f0f0;
    display: flex;
    align-items: center;
}

.chat-user-avatar {
    margin-right: 1rem;
}

.user-avatar, .user-avatar-placeholder {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    object-fit: cover;
}

.user-avatar-placeholder {
    background-color: var(--secondary);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 1.5rem;
}

.chat-user-name {
    font-size: 1.2rem;
    font-weight: 700;
    margin-bottom: 0.25rem;
}

.chat-user-location {
    font-size: 0.9rem;
    color: #666;
}

.chat-user-actions {
    padding: 1rem;
    border-bottom: 1px solid #f0f0f0;
}

.chat-main {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.chat-messages {
    flex: 1;
    overflow-y: auto;
    padding: 1rem;
    background-color: #f9fafc;
    display: flex;
    flex-direction: column;
}

.no-messages-placeholder {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: #aaa;
}

.placeholder-icon {
    font-size: 3rem;
    margin-bottom: 0.5rem;
}

.placeholder-text {
    font-size: 1.1rem;
}

.message {
    max-width: 70%;
    margin-bottom: 1rem;
    display: flex;
    flex-direction: column;
}

.message-sent {
    align-self: flex-end;
}

.message-received {
    align-self: flex-start;
}

.message-bubble {
    padding: 0.75rem 1rem;
    border-radius: 18px;
    word-break: break-word;
    line-height: 1.4;
}

.message-sent .message-bubble {
    background-color: var(--secondary);
    color: white;
    border-bottom-right-radius: 4px;
}

.message-received .message-bubble {
    background-color: #e9ecef;
    color: var(--dark);
    border-bottom-left-radius: 4px;
}

.message-time {
    font-size: 0.7rem;
    opacity: 0.7;
    margin-top: 0.25rem;
    align-self: flex-end;
}

.message-date-separator {
    width: 100%;
    text-align: center;
    margin: 1rem 0;
    position: relative;
}

.message-date-separator::before {
    content: '';
    position: absolute;
    left: 0;
    top: 50%;
    width: 100%;
    height: 1px;
    background-color: #e1e1e1;
}

.message-date-separator span {
    position: relative;
    background-color: #f9fafc;
    padding: 0 1rem;
    font-size: 0.8rem;
    color: #888;
}

.chat-input-form {
    padding: 1rem;
    border-top: 1px solid #f0f0f0;
    background-color: white;
}

.chat-input-container {
    position: relative;
    display: flex;
    align-items: center;
}

.chat-input {
    flex: 1;
    padding: 0.75rem;
    padding-right: 3rem;
    border: 1px solid #e1e1e1;
    border-radius: 24px;
    resize: none;
    max-height: 100px;
    min-height: 24px;
    line-height: 1.4;
}

.chat-input:focus {
    outline: none;
    border-color: var(--secondary);
    box-shadow: 0 0 0 2px rgba(108, 99, 255, 0.1);
}

.chat-send-btn {
    position: absolute;
    right: 10px;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background-color: var(--secondary);
    color: white;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
}

.chat-send-btn:hover {
    background-color: var(--primary);
    transform: scale(1.05);
}

.btn-sm {
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
}

@media (max-width: 768px) {
    .chat-container {
        flex-direction: column;
        height: auto;
    }
    
    .chat-sidebar {
        width: 100%;
        flex-direction: row;
        align-items: center;
    }
    
    .chat-user-info {
        flex: 1;
        border-bottom: none;
        border-right: 1px solid #f0f0f0;
    }
    
    .chat-user-actions {
        padding: 0.5rem 1rem;
        border-bottom: none;
    }
    
    .chat-main {
        height: 60vh;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Scroll to bottom of chat messages
    const chatMessages = document.getElementById('chat-messages');
    chatMessages.scrollTop = chatMessages.scrollHeight;
    
    // Auto-resize textarea
    const messageInput = document.getElementById('message-input');
    
    messageInput.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });
});
</script>
