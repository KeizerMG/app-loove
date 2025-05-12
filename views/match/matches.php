<div class="container py-8">
    <h1 class="text-3xl font-bold mb-6">Your Matches</h1>
    
    <?php if(empty($matches)): ?>
        <div class="bg-white rounded-lg shadow-md p-8 text-center">
            <div class="text-6xl mb-4">💔</div>
            <h2 class="text-2xl font-bold mb-3">No matches yet</h2>
            <p class="text-gray mb-6">Keep discovering profiles and you'll find your match soon!</p>
            <a href="<?= APP_URL ?>/discover" class="btn btn-primary">Discover Profiles</a>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach($matches as $match): ?>
                <div class="match-card bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="match-image-container">
                        <?php if(!empty($match['profile_picture'])): ?>
                            <img src="<?= APP_URL . '/' . $match['profile_picture'] ?>" alt="<?= htmlspecialchars($match['first_name']) ?>" class="match-image">
                        <?php else: ?>
                            <div class="match-default-image">
                                <i class="fas fa-user text-4xl text-gray"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="p-4">
                        <h3 class="text-xl font-bold">
                            <?= htmlspecialchars($match['first_name']) ?>, 
                            <?= (new DateTime($match['date_of_birth']))->diff(new DateTime())->y ?>
                        </h3>
                        
                        <?php if(!empty($match['location'])): ?>
                            <p class="text-gray text-sm mb-2">
                                <i class="fas fa-map-marker-alt mr-1"></i>
                                <?= htmlspecialchars($match['location']) ?>
                            </p>
                        <?php endif; ?>
                        
                        <p class="text-sm mb-4 line-clamp-2">
                            <?= !empty($match['bio']) ? htmlspecialchars(substr($match['bio'], 0, 100)) . (strlen($match['bio']) > 100 ? '...' : '') : 'No bio provided' ?>
                        </p>
                        
                        <div class="flex justify-between">
                            <a href="<?= APP_URL ?>/messages/conversation?user_id=<?= $match['matched_user_id'] ?>" class="btn btn-primary btn-sm">
                                <i class="fas fa-comment mr-2"></i> Message
                            </a>
                            <a href="<?= APP_URL ?>/profile/view?id=<?= $match['matched_user_id'] ?>" class="btn btn-outline btn-sm">
                                <i class="fas fa-user mr-2"></i> Profile
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<style>
.match-card {
    transition: all 0.3s ease;
}

.match-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-lg);
}

.match-image-container {
    height: 200px;
    overflow: hidden;
}

.match-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.match-default-image {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #f0f2fa;
}

.btn-sm {
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
