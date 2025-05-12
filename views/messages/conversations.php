<div class="container py-8">
    <h1 class="text-3xl font-bold mb-6">Messages</h1>
    
    <?php if(isset($_GET['error'])): ?>
        <div class="bg-danger bg-opacity-10 text-danger p-4 rounded-md mb-6">
            <?php
                $error = $_GET['error'];
                switch($error) {
                    case 'invalid_conversation':
                        echo 'Invalid conversation selected.';
                        break;
                    case 'not_matched':
                        echo 'You can only message with users you\'ve matched with.';
                        break;
                    case 'user_not_found':
                        echo 'User not found.';
                        break;
                    default:
                        echo 'An error occurred.';
                }
            ?>
        </div>
    <?php endif; ?>
    
    <?php if(empty($conversations)): ?>
        <div class="bg-white rounded-lg shadow-md p-8 text-center">
            <div class="text-6xl mb-4">💬</div>
            <h2 class="text-2xl font-bold mb-3">No messages yet</h2>
            <p class="text-gray mb-6">Start conversations with your matches to see them here!</p>
            <a href="<?= APP_URL ?>/matches" class="btn btn-primary">View Matches</a>
        </div>
    <?php else: ?>
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="conversations-list">
                <?php foreach($conversations as $conversation): ?>
                    <a href="<?= APP_URL ?>/messages/conversation?user_id=<?= $conversation['id'] ?>" 
                       class="conversation-item <?= $conversation['unread_count'] > 0 && !$conversation['sent_by_me'] ? 'unread' : '' ?>">
                        <div class="conversation-avatar">
                            <?php if(!empty($conversation['profile_picture'])): ?>
                                <img src="<?= APP_URL . '/' . $conversation['profile_picture'] ?>" 
                                     alt="<?= htmlspecialchars($conversation['first_name']) ?>" class="avatar-img">
                            <?php else: ?>
                                <div class="avatar-placeholder">
                                    <?= strtoupper(substr($conversation['first_name'], 0, 1)) ?>
                                </div>
                            <?php endif; ?>
                            
                            <?php if($conversation['unread_count'] > 0 && !$conversation['sent_by_me']): ?>
                                <span class="unread-badge"><?= $conversation['unread_count'] ?></span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="conversation-info">
                            <div class="conversation-header">
                                <h3 class="conversation-name"><?= htmlspecialchars($conversation['first_name']) ?></h3>
                                <span class="conversation-time">
                                    <?= $this->formatMessageTime($conversation['created_at']) ?>
                                </span>
                            </div>
                            <p class="conversation-message">
                                <?php if($conversation['sent_by_me']): ?>
                                    <span class="text-gray">You: </span>
                                <?php endif; ?>
                                <?= htmlspecialchars(substr($conversation['message'], 0, 50)) . (strlen($conversation['message']) > 50 ? '...' : '') ?>
                            </p>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<style>
.conversations-list {
    max-height: 80vh;
    overflow-y: auto;
}

.conversation-item {
    display: flex;
    align-items: center;
    padding: 1rem;
    border-bottom: 1px solid #f0f0f0;
    transition: all 0.2s ease;
}

.conversation-item:hover {
    background-color: #f9f9f9;
}

.conversation-item.unread {
    background-color: rgba(108, 99, 255, 0.05);
}

.conversation-avatar {
    position: relative;
    margin-right: 1rem;
}

.avatar-img, .avatar-placeholder {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
}

.avatar-placeholder {
    background-color: var(--secondary);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 1.2rem;
}

.unread-badge {
    position: absolute;
    top: -5px;
    right: -5px;
    background-color: var(--primary);
    color: white;
    font-size: 0.7rem;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.conversation-info {
    flex: 1;
    min-width: 0;
}

.conversation-header {
    display: flex;
    justify-content: space-between;
    margin-bottom: 2px;
}

.conversation-name {
    font-weight: 600;
}

.conversation-time {
    font-size: 0.8rem;
    color: #888;
}

.conversation-message {
    color: #666;
    font-size: 0.9rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.conversation-item.unread .conversation-name {
    font-weight: 700;
    color: var(--dark);
}

.conversation-item.unread .conversation-message {
    font-weight: 500;
    color: var(--dark);
}
</style>

<?php

function formatMessageTime($timestamp) {
    $now = new DateTime();
    $msgTime = new DateTime($timestamp);
    $diff = $now->diff($msgTime);
    
    if ($diff->y > 0) {
        return $msgTime->format('M j, Y');
    } elseif ($diff->d > 0) {
        if ($diff->d == 1) {
            return 'Yesterday';
        }
        if ($diff->d < 7) {
            return $msgTime->format('l');
        }
        return $msgTime->format('M j'); 
    } else {
        return $msgTime->format('g:i A'); 
    }
}
?>
