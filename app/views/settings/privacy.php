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
    
    .privacy-option {
        margin-bottom: 2rem;
    }
    
    .privacy-option:last-child {
        margin-bottom: 0;
    }
    
    .privacy-option-title {
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    
    .privacy-option-desc {
        color: var(--color-text-secondary);
        font-size: 0.9rem;
        margin-bottom: 1rem;
    }
    
    .radio-group {
        display: flex;
        gap: 1.5rem;
    }
    
    .radio-option {
        display: flex;
        align-items: center;
    }
    
    .radio-option input[type="radio"] {
        margin-right: 0.5rem;
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
        
        .radio-group {
            flex-direction: column;
            gap: 0.5rem;
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
                <a href="<?php echo BASEURL; ?>/settings/notifications" class="settings-menu-item">
                    <i class="fas fa-bell"></i>
                    Notifications
                </a>
                <a href="<?php echo BASEURL; ?>/settings/privacy" class="settings-menu-item active">
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
                <h2>Paramètres de confidentialité</h2>
                <p style="margin-bottom: 1.5rem; color: var(--color-text-secondary);">Contrôlez qui peut voir votre profil et vos informations</p>
                
                <form action="<?php echo BASEURL; ?>/settings/privacy" method="POST">
                    <div class="privacy-option">
                        <div class="privacy-option-title">Visibilité du profil</div>
                        <div class="privacy-option-desc">Choisissez qui peut voir votre profil dans les résultats de recherche</div>
                        
                        <div class="radio-group">
                            <label class="radio-option">
                                <input type="radio" name="profile_visibility" value="public" <?php echo $data['profile_visibility'] === 'public' ? 'checked' : ''; ?>>
                                Tout le monde
                            </label>
                            <label class="radio-option">
                                <input type="radio" name="profile_visibility" value="matches" <?php echo $data['profile_visibility'] === 'matches' ? 'checked' : ''; ?>>
                                Seulement mes matchs
                            </label>
                            <label class="radio-option">
                                <input type="radio" name="profile_visibility" value="nobody" <?php echo $data['profile_visibility'] === 'nobody' ? 'checked' : ''; ?>>
                                Personne (mode invisible)
                            </label>
                        </div>
                    </div>
                    
                    <div class="privacy-option">
                        <div class="privacy-option-title">Statut en ligne</div>
                        <div class="privacy-option-desc">Choisissez si les autres utilisateurs peuvent voir quand vous êtes en ligne</div>
                        
                        <div style="display: flex; align-items: center;">
                            <label class="switch" style="margin-right: 1rem;">
                                <input type="checkbox" name="show_online_status" <?php echo $data['show_online_status'] ? 'checked' : ''; ?>>
                                <span class="slider"></span>
                            </label>
                            <span>Afficher mon statut en ligne</span>
                        </div>
                    </div>
                    
                    <div class="privacy-option">
                        <div class="privacy-option-title">Dernière activité</div>
                        <div class="privacy-option-desc">Choisissez si les autres utilisateurs peuvent voir quand vous étiez actif pour la dernière fois</div>
                        
                        <div style="display: flex; align-items: center;">
                            <label class="switch" style="margin-right: 1rem;">
                                <input type="checkbox" name="show_last_active" <?php echo $data['show_last_active'] ? 'checked' : ''; ?>>
                                <span class="slider"></span>
                            </label>
                            <span>Afficher ma dernière activité</span>
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
