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
    
    .chat-placeholder {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        color: var(--color-text-tertiary);
        text-align: center;
        padding: 2rem;
    }
    
    .chat-placeholder i {
        font-size: 4rem;
        margin-bottom: 1rem;
        color: var(--color-primary-soft);
    }
    
    .no-conversations {
        text-align: center;
        padding: 2rem;
        color: var(--color-text-secondary);
    }
    
    /* Styles pour les informations temporelles dans la liste des conversations */
    .conversation-time-info {
        font-size: 0.7rem;
        color: var(--color-text-tertiary);
        margin-top: 0.2rem;
    }
    
    /* Style pour l'animation de nouveaux messages */
    @keyframes pulse-highlight {
        0% { background-color: var(--color-primary-soft); }
        50% { background-color: rgba(187, 134, 252, 0.2); }
        100% { background-color: var(--color-primary-soft); }
    }
    
    .conversation-item.has-unread {
        animation: pulse-highlight 2s infinite;
    }
    
    /* Badge "En ligne" */
    .online-badge {
        display: inline-block;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background-color: var(--color-success);
        margin-left: 6px;
        position: relative;
    }
    
    .online-badge::after {
        content: '';
        position: absolute;
        top: -2px;
        left: -2px;
        right: -2px;
        bottom: -2px;
        border-radius: 50%;
        background-color: var(--color-success);
        opacity: 0.4;
        animation: pulse 1.5s infinite;
    }
    
    @keyframes pulse {
        0% { transform: scale(1); opacity: 0.4; }
        50% { transform: scale(1.5); opacity: 0.1; }
        100% { transform: scale(1); opacity: 0.4; }
    }
</style>

<section class="messages-loove-section slide-up">
    <div style="margin-bottom: 1.5rem;">
        <h1 class="section-title-loove">Mes Messages</h1>
        <p class="section-subtitle-loove">Retrouvez toutes vos conversations et continuez à échanger avec vos matchs.</p>
    </div>

    <?php flash('messages_error'); ?>

    <div class="messages-container">
        <div class="conversations-list">
            <?php if(!empty($data['conversations'])) : ?>
                <?php foreach($data['conversations'] as $conversation) : 
                    $hasUnread = isset($conversation->unread_count) && $conversation->unread_count > 0;
                    // Déterminer si l'utilisateur est "en ligne" (aléatoire pour les utilisateurs factices)
                    $isOnline = isset($conversation->is_fake) && $conversation->is_fake && rand(0, 4) == 0;
                    
                    // Formater le temps du dernier message
                    $timeAgo = '';
                    if(isset($conversation->last_message_time)) {
                        $messageTime = strtotime($conversation->last_message_time);
                        $now = time();
                        $diff = $now - $messageTime;
                        
                        if($diff < 60) {
                            $timeAgo = 'À l\'instant';
                        } elseif($diff < 3600) {
                            $timeAgo = floor($diff / 60) . ' min';
                        } elseif($diff < 86400) {
                            $timeAgo = floor($diff / 3600) . ' h';
                        } elseif($diff < 172800) {
                            $timeAgo = 'Hier';
                        } elseif($diff < 604800) {
                            $timeAgo = floor($diff / 86400) . ' j';
                        } else {
                            $timeAgo = date('d/m', $messageTime);
                        }
                    }
                ?>
                    <a href="<?php echo BASEURL; ?>/messages/with/<?php echo $conversation->id; ?>" class="conversation-item <?php echo $hasUnread ? 'has-unread' : ''; ?>">
                        <img src="<?php echo (strpos($conversation->profile_pic, 'https://') === 0) ? $conversation->profile_pic : BASEURL . '/img/profiles/' . (!empty($conversation->profile_pic) ? $conversation->profile_pic : 'default.jpg'); ?>" alt="Photo de <?php echo $conversation->first_name; ?>" class="conversation-avatar">
                        <div class="conversation-info">
                            <div class="conversation-header">
                                <h3 class="conversation-name">
                                    <?php echo $conversation->first_name; ?>, <?php echo $conversation->age; ?>
                                    <?php if($isOnline): ?>
                                        <span class="online-badge" title="En ligne"></span>
                                    <?php endif; ?>
                                </h3>
                                <?php if(isset($conversation->last_message_time)) : ?>
                                    <span class="conversation-time"><?php echo $timeAgo; ?></span>
                                <?php endif; ?>
                            </div>
                            <p class="conversation-last-message">
                                <?php if(isset($conversation->last_message)) : ?>
                                    <?php echo ($conversation->last_message_sender == $_SESSION['user_id']) ? 'Vous: ' : ''; ?>
                                    <?php echo substr($conversation->last_message, 0, 30); ?>
                                    <?php echo (strlen($conversation->last_message) > 30) ? '...' : ''; ?>
                                <?php else : ?>
                                    <em>Aucun message</em>
                                <?php endif; ?>
                                <?php if($hasUnread) : ?>
                                    <span class="unread-badge"><?php echo $conversation->unread_count; ?></span>
                                <?php endif; ?>
                            </p>
                            <?php if(isset($conversation->location)) : ?>
                                <p class="conversation-time-info">
                                    <i class="fas fa-map-marker-alt" style="font-size: 0.8rem;"></i> <?php echo $conversation->location; ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php else : ?>
                <div class="no-conversations">
                    <i class="fas fa-comment-slash fa-3x" style="margin-bottom: 1rem;"></i>
                    <h3>Aucune conversation</h3>
                    <p>Vous n'avez pas encore de matchs ou de conversations.</p>
                    <a href="<?php echo BASEURL; ?>/discover" class="btn-loove btn-loove-primary" style="margin-top: 1rem;">Découvrir des profils</a>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="chat-placeholder">
            <i class="fas fa-comments"></i>
            <h2>Vos messages</h2>
            <p>Sélectionnez une conversation pour commencer à discuter.</p>
        </div>
    </div>
</section>

<?php require APPROOT . '/views/includes/footer.php'; ?>
