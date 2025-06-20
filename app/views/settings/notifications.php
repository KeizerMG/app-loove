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
    
    .notification-option {
        display: flex;
        align-items: flex-start;
        padding: 1rem 0;
        border-bottom: 1px solid var(--color-divider);
    }
    
    .notification-option:last-child {
        border-bottom: none;
    }
    
    .notification-switch {
        margin-right: 1rem;
        margin-top: 0.25rem;
    }
    
    .notification-info {
        flex: 1;
    }
    
    .notification-title {
        font-weight: 600;
        margin-bottom: 0.25rem;
    }
    
    .notification-desc {
        color: var(--color-text-secondary);
        font-size: 0.9rem;
    }
    
    .switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 24px;
    }
    
    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: var(--transition-normal);
        border-radius: 34px;
    }
    
    .slider:before {
        position: absolute;
        content: "";
        height: 16px;
        width: 16px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: var(--transition-normal);
        border-radius: 50%;
    }
    
    input:checked + .slider {
        background-color: var(--color-primary);
    }
    
    input:focus + .slider {
        box-shadow: 0 0 1px var(--color-primary);
    }
    
    input:checked + .slider:before {
        transform: translateX(26px);
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
                <a href="<?php echo BASEURL; ?>/settings" class="settings-menu-item">
                    <i class="fas fa-lock"></i>
                    Sécurité
                </a>
                <a href="<?php echo BASEURL; ?>/settings/notifications" class="settings-menu-item active">
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
                <h2>Paramètres de notifications</h2>
                <p style="margin-bottom: 1.5rem; color: var(--color-text-secondary);">Gérez la façon dont vous souhaitez être informé des activités importantes</p>
                
                <form action="<?php echo BASEURL; ?>/settings/notifications" method="POST">
                    <div class="notification-option">
                        <div class="notification-switch">
                            <label class="switch">
                                <input type="checkbox" name="email_notifications" <?php echo $data['email_notifications'] ? 'checked' : ''; ?>>
                                <span class="slider"></span>
                            </label>
                        </div>
                        <div class="notification-info">
                            <div class="notification-title">Notifications par e-mail</div>
                            <div class="notification-desc">Recevez des notifications par e-mail lorsque quelqu'un vous like, vous envoie un message ou pour d'autres activités importantes.</div>
                        </div>
                    </div>
                    
                    <div class="notification-option">
                        <div class="notification-switch">
                            <label class="switch">
                                <input type="checkbox" name="app_notifications" <?php echo $data['app_notifications'] ? 'checked' : ''; ?>>
                                <span class="slider"></span>
                            </label>
                        </div>
                        <div class="notification-info">
                            <div class="notification-title">Notifications dans l'application</div>
                            <div class="notification-desc">Recevez des notifications dans l'application pour les likes, messages et autres activités.</div>
                        </div>
                    </div>
                    
                    <div class="notification-option">
                        <div class="notification-switch">
                            <label class="switch">
                                <input type="checkbox" name="marketing_emails" <?php echo $data['marketing_emails'] ? 'checked' : ''; ?>>
                                <span class="slider"></span>
                            </label>
                        </div>
                        <div class="notification-info">
                            <div class="notification-title">E-mails marketing</div>
                            <div class="notification-desc">Recevez des informations sur les promotions, les nouveautés et les conseils pour améliorer votre profil.</div>
                        </div>
                    </div>
                    
                    <div style="margin-top: 2rem;">
                        <button type="submit" class="btn-loove btn-loove-primary">
                            <i class="fas fa-save"></i> Enregistrer les préférences
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/includes/footer.php'; ?>
