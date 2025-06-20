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
        margin-bottom: 1rem;
    }
    
    .danger-zone p {
        margin-bottom: 1.5rem;
    }
    
    .delete-confirmation {
        display: none;
        margin-top: 1.5rem;
        padding: 1rem;
        border: 1px solid rgba(239, 68, 68, 0.3);
        border-radius: var(--border-radius-md);
        background-color: rgba(239, 68, 68, 0.05);
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
                <a href="<?php echo BASEURL; ?>/settings/notifications" class="settings-menu-item">
                    <i class="fas fa-bell"></i>
                    Notifications
                </a>
                <a href="<?php echo BASEURL; ?>/settings/privacy" class="settings-menu-item">
                    <i class="fas fa-user-shield"></i>
                    Confidentialité
                </a>
                <a href="<?php echo BASEURL; ?>/settings/account" class="settings-menu-item active">
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
                <h2>Paramètres du compte</h2>
                <p style="margin-bottom: 1.5rem; color: var(--color-text-secondary);">Gérez les informations de votre compte</p>
                
                <div class="form-group-loove">
                    <label class="form-label-loove">Adresse e-mail</label>
                    <div style="display: flex; gap: 1rem;">
                        <input type="email" value="<?php echo $_SESSION['user_email'] ?? ''; ?>" class="form-control-loove" readonly style="flex: 1;">
                        <button class="btn-loove btn-loove-outline" disabled>Modifier</button>
                    </div>
                    <small style="display: block; margin-top: 0.5rem; color: var(--color-text-tertiary);">La modification de l'adresse e-mail n'est pas disponible pour le moment.</small>
                </div>
                
                <div class="form-group-loove">
                    <a href="<?php echo BASEURL; ?>/profiles/edit/<?php echo $_SESSION['user_id']; ?>" class="btn-loove btn-loove-primary">
                        <i class="fas fa-user-edit"></i> Modifier mon profil
                    </a>
                </div>
                
                <div class="danger-zone">
                    <h3><i class="fas fa-exclamation-triangle"></i> Zone dangereuse</h3>
                    <p>La suppression de votre compte est définitive et entraînera la perte de toutes vos données, y compris vos matchs et vos conversations.</p>
                    
                    <button id="showDeleteConfirmation" class="btn-loove btn-loove-error">
                        <i class="fas fa-trash-alt"></i> Supprimer mon compte
                    </button>
                    
                    <div id="deleteConfirmation" class="delete-confirmation">
                        <p style="font-weight: 600; margin-bottom: 1rem;">Êtes-vous vraiment sûr de vouloir supprimer votre compte ?</p>
                        <p>Pour confirmer, veuillez taper "delete" ci-dessous :</p>
                        
                        <form action="<?php echo BASEURL; ?>/settings/deleteAccount" method="POST">
                            <div class="form-group-loove">
                                <input type="text" name="confirm_delete" class="form-control-loove" placeholder="Tapez 'delete' pour confirmer" required>
                            </div>
                            
                            <div style="display: flex; gap: 1rem; margin-top: 1rem;">
                                <button type="button" id="cancelDelete" class="btn-loove btn-loove-outline">Annuler</button>
                                <button type="submit" class="btn-loove btn-loove-error">Supprimer définitivement</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('showDeleteConfirmation').addEventListener('click', function() {
        document.getElementById('deleteConfirmation').style.display = 'block';
        this.style.display = 'none';
    });
    
    document.getElementById('cancelDelete').addEventListener('click', function() {
        document.getElementById('deleteConfirmation').style.display = 'none';
        document.getElementById('showDeleteConfirmation').style.display = 'inline-block';
    });
</script>

<?php require APPROOT . '/views/includes/footer.php'; ?>
