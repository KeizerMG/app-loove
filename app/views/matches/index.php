<?php require APPROOT . '/views/includes/header.php'; ?>

<style>
    .matches-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.5rem;
    }
    
    .match-card {
        background-color: var(--color-surface);
        border-radius: var(--border-radius-lg);
        overflow: hidden;
        box-shadow: var(--shadow-md);
        transition: var(--transition-normal);
        display: flex;
        flex-direction: column;
        height: 100%;
    }
    
    .match-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-lg);
    }
    
    .match-image {
        position: relative;
        height: 200px;
        overflow: hidden;
    }
    
    .match-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    
    .match-card:hover .match-image img {
        transform: scale(1.05);
    }
    
    .match-badge {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background-color: var(--color-primary);
        color: white;
        font-size: 0.8rem;
        font-weight: 600;
        padding: 0.25rem 0.75rem;
        border-radius: var(--border-radius-full);
        box-shadow: var(--shadow-sm);
    }
    
    .match-details {
        padding: 1.25rem;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }
    
    .match-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.75rem;
    }
    
    .match-name {
        font-size: 1.3rem;
        font-weight: 700;
        margin: 0;
    }
    
    .match-age {
        font-size: 1.1rem;
        font-weight: 400;
        color: var(--color-text-secondary);
        margin-left: 0.25rem;
    }
    
    .match-date {
        font-size: 0.85rem;
        color: var(--color-text-tertiary);
    }
    
    .match-info {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }
    
    .match-info-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.9rem;
        color: var(--color-text-secondary);
    }
    
    .match-info-item i {
        color: var(--color-primary);
        width: 20px;
        text-align: center;
    }
    
    .match-message {
        background-color: var(--color-surface-variant);
        padding: 1rem;
        border-radius: var(--border-radius-md);
        font-size: 0.9rem;
        color: var(--color-text-secondary);
        margin-bottom: 1rem;
        flex-grow: 1;
        position: relative;
    }
    
    .match-message.no-message {
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--color-text-tertiary);
        font-style: italic;
    }
    
    .unread-badge {
        position: absolute;
        top: -0.5rem;
        right: -0.5rem;
        background-color: var(--color-primary);
        color: white;
        font-size: 0.7rem;
        font-weight: 600;
        min-width: 20px;
        height: 20px;
        border-radius: var(--border-radius-full);
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: var(--shadow-sm);
    }
    
    .match-actions {
        display: flex;
        gap: 0.75rem;
    }
    
    .match-btn {
        flex: 1;
        padding: 0.6rem;
        font-size: 0.9rem;
    }
    
    .no-matches {
        text-align: center;
        background-color: var(--color-surface);
        padding: 3rem 2rem;
        border-radius: var(--border-radius-lg);
        box-shadow: var(--shadow-md);
    }
    
    .no-matches i {
        font-size: 3rem;
        color: var(--color-tertiary);
        margin-bottom: 1.5rem;
    }
    
    .match-time {
        font-size: 0.8rem;
        color: var(--color-text-tertiary);
        margin-top: 0.5rem;
        text-align: right;
    }
</style>

<section class="matches-section slide-up">
    <div style="margin-bottom: 2.5rem; text-align: center;">
        <h1 class="section-title-loove"><?php echo $data['title']; ?></h1>
        <p class="section-subtitle-loove">Retrouvez toutes les personnes avec qui vous avez matché et commencez à discuter !</p>
    </div>
    
    <?php if(!empty($data['matches'])) : ?>
        <div class="matches-container">
            <?php foreach($data['matches'] as $match) : 
                $user = $match['user'];
                $age = $match['age'];
                $profile = $match['profile'];
                $matchDate = new DateTime($match['match_date']);
                $now = new DateTime();
                $matchInterval = $matchDate->diff($now);
                
                // Formater la date du match de façon conviviale
                if($matchInterval->days == 0) {
                    $matchDateFormatted = "Aujourd'hui";
                } elseif($matchInterval->days == 1) {
                    $matchDateFormatted = "Hier";
                } elseif($matchInterval->days < 7) {
                    $matchDateFormatted = "Il y a " . $matchInterval->days . " jours";
                } elseif($matchInterval->days < 30) {
                    $matchDateFormatted = "Il y a " . ceil($matchInterval->days / 7) . " semaines";
                } else {
                    $matchDateFormatted = "Le " . $matchDate->format('d/m/Y');
                }
                
                // Formater la date du dernier message
                $lastMessageFormatted = '';
                if(!empty($match['last_message_time'])) {
                    $lastMessageTime = new DateTime($match['last_message_time']);
                    $lastMessageInterval = $lastMessageTime->diff($now);
                    
                    if($lastMessageInterval->days == 0) {
                        if($lastMessageInterval->h == 0) {
                            if($lastMessageInterval->i == 0) {
                                $lastMessageFormatted = "À l'instant";
                            } else {
                                $lastMessageFormatted = "Il y a " . $lastMessageInterval->i . " min";
                            }
                        } else {
                            $lastMessageFormatted = "Il y a " . $lastMessageInterval->h . " h";
                        }
                    } elseif($lastMessageInterval->days == 1) {
                        $lastMessageFormatted = "Hier";
                    } else {
                        $lastMessageFormatted = "Il y a " . $lastMessageInterval->days . " jours";
                    }
                }
            ?>
            <div class="match-card">
                <div class="match-image">
                    <img src="<?php echo (strpos($user->profile_pic, 'https://') === 0) ? $user->profile_pic : BASEURL . '/img/profiles/' . (!empty($user->profile_pic) ? $user->profile_pic : 'default.jpg'); ?>" alt="Photo de <?php echo $user->first_name; ?>">
                    <div class="match-badge">MATCH</div>
                </div>
                <div class="match-details">
                    <div class="match-header">
                        <h3 class="match-name">
                            <?php echo $user->first_name; ?>
                            <span class="match-age"><?php echo $age; ?></span>
                        </h3>
                        <span class="match-date"><?php echo $matchDateFormatted; ?></span>
                    </div>
                    
                    <div class="match-info">
                        <?php if(!empty($profile) && !empty($profile->location)) : ?>
                        <div class="match-info-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <span><?php echo $profile->location; ?></span>
                        </div>
                        <?php endif; ?>
                        
                        <?php if(!empty($profile) && !empty($profile->relationship_type)) : ?>
                        <div class="match-info-item">
                            <i class="fas fa-heart"></i>
                            <span>Recherche: <?php echo ucfirst($profile->relationship_type); ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="match-message <?php echo empty($match['last_message']) ? 'no-message' : ''; ?>">
                        <?php if(!empty($match['last_message'])) : ?>
                            <?php echo htmlspecialchars(substr($match['last_message'], 0, 100)); ?>
                            <?php echo (strlen($match['last_message']) > 100) ? '...' : ''; ?>
                            <?php if($match['unread_count'] > 0) : ?>
                                <div class="unread-badge"><?php echo $match['unread_count']; ?></div>
                            <?php endif; ?>
                            <div class="match-time"><?php echo $lastMessageFormatted; ?></div>
                        <?php else : ?>
                            <span>Aucun message échangé</span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="match-actions">
                        <a href="<?php echo BASEURL; ?>/messages/with/<?php echo $user->id; ?>" class="btn-loove btn-loove-primary match-btn">
                            <i class="fas fa-comment"></i> Message
                        </a>
                        <a href="<?php echo BASEURL; ?>/profiles/show/<?php echo $user->id; ?>" class="btn-loove btn-loove-outline match-btn">
                            <i class="fas fa-user"></i> Profil
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php else : ?>
        <div class="no-matches">
            <i class="fas fa-heart-broken"></i>
            <h2>Aucun match pour le moment</h2>
            <p>Continuez à explorer les profils pour trouver des personnes qui vous correspondent.</p>
            <a href="<?php echo BASEURL; ?>/discover" class="btn-loove btn-loove-primary" style="margin-top: 1.5rem;">
                <i class="fas fa-compass"></i> Découvrir des profils
            </a>
        </div>
    <?php endif; ?>
</section>

<?php require APPROOT . '/views/includes/footer.php'; ?>
