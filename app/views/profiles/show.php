<?php require APPROOT . '/views/includes/header.php'; ?>

<div class="profile-show-container slide-up">
    <?php if (isset($data['user']) && $data['user']): ?>
        <div class="profile-header">
            <img src="<?php echo (strpos($data['user']->profile_pic, 'https://') === 0) ? htmlspecialchars($data['user']->profile_pic) : BASEURL . '/img/profiles/' . htmlspecialchars(!empty($data['user']->profile_pic) ? $data['user']->profile_pic : 'default.jpg'); ?>" 
                 alt="Photo de profil de <?php echo htmlspecialchars($data['user']->first_name); ?>" class="profile-avatar-show">
            <div class="profile-name-age">
                <h1><?php echo htmlspecialchars($data['user']->first_name); ?> <?php echo substr(htmlspecialchars($data['user']->last_name), 0, 1); ?>.</h1>
                <p><?php echo htmlspecialchars($data['age']); ?> ans</p>
            </div>
        </div>

        <div class="profile-details-grid">
            <?php if (isset($data['profileDetails']) && !empty($data['profileDetails']->location)): ?>
            <div class="profile-detail-item">
                <strong>Localisation</strong>
                <span><?php echo htmlspecialchars($data['profileDetails']->location); ?></span>
            </div>
            <?php endif; ?>

            <div class="profile-detail-item">
                <strong>Genre</strong>
                <span><?php echo ucfirst(htmlspecialchars($data['user']->gender)); ?></span>
            </div>

            <?php if (isset($data['profileDetails']) && !empty($data['profileDetails']->relationship_type)): ?>
            <div class="profile-detail-item">
                <strong>Recherche</strong>
                <span><?php echo ucfirst(htmlspecialchars($data['profileDetails']->relationship_type)); ?></span>
            </div>
            <?php endif; ?>
        </div>
        
        <?php if (isset($data['profileDetails']) && !empty($data['profileDetails']->bio)): ?>
        <div class="profile-bio-section">
            <h3>À propos de moi</h3>
            <p><?php echo nl2br(htmlspecialchars($data['profileDetails']->bio)); ?></p>
        </div>
        <?php endif; ?>

        <?php if(isLoggedIn() && isset($_SESSION['user_id']) && $_SESSION['user_id'] != $data['user']->id): ?>
        <div class="profile-actions">
            <a href="#" class="btn-loove btn-loove-primary">
                <i class="fas fa-heart"></i> J'aime
            </a>
            <a href="#" class="btn-loove btn-loove-secondary">
                <i class="fas fa-comment"></i> Message
            </a>
        </div>
        <?php elseif(isLoggedIn() && isset($_SESSION['user_id']) && $_SESSION['user_id'] == $data['user']->id): ?>
         <div class="profile-actions">
            <a href="<?php echo BASEURL; ?>/profiles/edit/<?php echo $_SESSION['user_id']; ?>" class="btn-loove btn-loove-primary">
                <i class="fas fa-edit"></i> Modifier mon profil
            </a>
        </div>
        <?php endif; ?>

    <?php else: ?>
        <div style="text-align: center; padding: 3rem;">
            <i class="fas fa-exclamation-triangle fa-3x" style="color: var(--color-error); margin-bottom: 1.5rem;"></i>
            <h3 style="margin-bottom: 1rem;">Profil non trouvé</h3>
            <p style="color: var(--color-text-secondary); margin-bottom: 1.5rem;">Le profil que vous recherchez n'existe pas ou n'est plus disponible.</p>
            <a href="<?php echo BASEURL; ?>/discover" class="btn-loove btn-loove-secondary">Retour à la découverte</a>
        </div>
    <?php endif; ?>
</div>

<?php require APPROOT . '/views/includes/footer.php'; ?>
