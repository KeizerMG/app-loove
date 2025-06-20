<?php require APPROOT . '/views/includes/header.php'; ?>

<style>
    .settings-container {
        display: flex;
        gap: 2rem;
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .settings-sidebar {
        flex: 0 0 250px;
    }
    
    .settings-main {
        flex: 1;
    }
    
    .settings-menu {
        background-color: var(--color-surface);
        border-radius: var(--border-radius-lg);
        box-shadow: var(--shadow-md);
        overflow: hidden;
    }
    
    .settings-menu-item {
        display: flex;
        align-items: center;
        padding: 1rem 1.5rem;
        color: var(--color-text-primary);
        text-decoration: none;
        border-bottom: 1px solid var(--color-divider);
        transition: var(--transition-normal);
    }
    
    .settings-menu-item:last-child {
        border-bottom: none;
    }
    
    .settings-menu-item:hover {
        background-color: var(--color-surface-variant);
    }
    
    .settings-menu-item.active {
        background-color: var(--color-primary-soft);
        color: var(--color-primary);
        font-weight: 600;
    }
    
    .settings-menu-item i {
        margin-right: 1rem;
        width: 20px;
        text-align: center;
    }
    
    .settings-card {
        background-color: var(--color-surface);
        border-radius: var(--border-radius-lg);
        box-shadow: var(--shadow-md);
        padding: 2rem;
        margin-bottom: 2rem;
    }
    
    .settings-card h2 {
        margin-top: 0;
        margin-bottom: 1.5rem;
        font-size: 1.5rem;
        color: var(--color-text-primary);
    }
    
    .user-info {
        display: flex;
        align-items: center;
        margin-bottom: 2rem;
    }
    
    .user-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        object-fit: cover;
        margin-right: 1.5rem;
    }
    
    .user-details {
        flex: 1;
    }
    
    .user-name {
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 0.25rem;
    }
    
    .user-email {
        color: var(--color-text-secondary);
        margin-bottom: 0.5rem;
    }
    
    .subscription-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: var(--border-radius-full);
        font-size: 0.8rem;
        font-weight: 600;
    }
    
    .subscription-free {
        background-color: var(--color-surface-variant);
        color: var(--color-text-secondary);
    }
    
    .subscription-premium {
        background-color: var(--color-primary-soft);
        color: var(--color-primary);
    }
    
    .danger-zone {
        background-color: rgba(239, 68, 68, 0.1);
        border: 1px solid rgba(239, 68, 68, 0.3);
        border-radius: var(--border-radius-lg);
        padding: 1.5rem;
        margin-top: 2rem;
    }
    
    .danger-zone h3 {
        color: var(--color-error);
        margin-top: 0;
    }
    
    @media (max-width: 768px) {
        .settings-container {
            flex-direction: column;
        }
        
        .settings-sidebar {
            flex: auto;
        }
    }
</style>

<div class="settings-section slide-up">
    <div style="margin-bottom: 2rem;">
        <h1 class="section-title-loove">Paramètres</h1>
        <p class="section-subtitle-loove">Gérez vos préférences et vos informations personnelles</p>
    </div>
    
    <?php flash('settings_success'); ?>
    <?php flash('settings_error'); ?>
    
    <div class="settings-container">
        <div class="settings-sidebar">
            <div class="settings-menu">
                <a href="<?php echo BASEURL; ?>/settings" class="settings-menu-item active">
                    <i class="fas fa-lock"></i>
                    Sécurité
                </a>
                <a href="<?php echo BASEURL; ?>/settings/notifications" class="settings-menu-item">
                    <i class="fas fa-bell"></i>
                    Notifications
                </a>
                <a href="<?php echo BASEURL; ?>/settings/privacy" class="settings-menu-item">
                    <i class="fas fa-user-shield"></i>
                    Confidentialité
                </a>
                <a href="<?php echo BASEURL; ?>/settings/account" class="settings-menu-item">
                    <i class="fas fa-user-cog"></i>
                    Compte
                </a>
                <a href="<?php echo BASEURL; ?>/subscriptions" class="settings-menu-item">
                    <i class="fas fa-crown"></i>
                    Abonnement
                </a>
                <a href="<?php echo BASEURL; ?>/help" class="settings-menu-item">
                    <i class="fas fa-question-circle"></i>
                    Aide
                </a>
            </div>
        </div>
        
        <div class="settings-main">
            <div class="settings-card">
                <div class="user-info">
                    <img src="<?php echo (strpos($data['user']->profile_pic, 'https://') === 0) ? $data['user']->profile_pic : BASEURL . '/img/profiles/' . (!empty($data['user']->profile_pic) ? $data['user']->profile_pic : 'default.jpg'); ?>" alt="Photo de profil" class="user-avatar">
                    <div class="user-details">
                        <div class="user-name"><?php echo $data['user']->first_name . ' ' . $data['user']->last_name; ?></div>
                        <div class="user-email"><?php echo $data['user']->email; ?></div>
                        <?php if(isset($data['active_subscription']) && $data['active_subscription']) : ?>
                            <div class="subscription-badge subscription-premium"><?php echo $data['active_subscription']->plan_name; ?></div>
                        <?php else : ?>
                            <div class="subscription-badge subscription-free">Compte Gratuit</div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <h2>Modifier votre mot de passe</h2>
                <form action="<?php echo BASEURL; ?>/settings" method="POST">
                    <div class="form-group-loove">
                        <label for="current_password" class="form-label-loove">Mot de passe actuel</label>
                        <input type="password" id="current_password" name="current_password" class="form-control-loove <?php echo (!empty($data['current_password_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['current_password']; ?>">
                        <span class="invalid-feedback"><?php echo $data['current_password_err']; ?></span>
                    </div>
                    
                    <div class="form-group-loove">
                        <label for="new_password" class="form-label-loove">Nouveau mot de passe</label>
                        <input type="password" id="new_password" name="new_password" class="form-control-loove <?php echo (!empty($data['new_password_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['new_password']; ?>">
                        <span class="invalid-feedback"><?php echo $data['new_password_err']; ?></span>
                    </div>
                    
                    <div class="form-group-loove">
                        <label for="confirm_password" class="form-label-loove">Confirmer le nouveau mot de passe</label>
                        <input type="password" id="confirm_password" name="confirm_password" class="form-control-loove <?php echo (!empty($data['confirm_password_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['confirm_password']; ?>">
                        <span class="invalid-feedback"><?php echo $data['confirm_password_err']; ?></span>
                    </div>
                    
                    <div class="form-group-loove">
                        <button type="submit" class="btn-loove btn-loove-primary">
                            <i class="fas fa-save"></i> Enregistrer les modifications
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/includes/footer.php'; ?>
