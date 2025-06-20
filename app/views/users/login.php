<?php require APPROOT . '/views/includes/header.php'; ?>

<section class="auth-container-loove">
    <div class="auth-card-loove">
        <h2>Connexion</h2>
        <p class="auth-subtitle">Connectez-vous pour accéder à votre compte</p>
        
        <?php 
        // Afficher un message spécial si l'utilisateur a été banni
        if(isset($_GET['banned']) && $_GET['banned'] == '1') {
            $reason = isset($_GET['reason']) ? htmlspecialchars($_GET['reason']) : 'Aucune raison spécifiée';
            echo '<div class="alert alert-loove-danger" style="margin-bottom: 1.5rem;">
                    <i class="fas fa-ban"></i> 
                    Votre compte a été suspendu. Raison: ' . $reason . '
                  </div>';
        }
        
        flash('message'); 
        ?>
        
        <form action="<?php echo BASEURL; ?>/users/login" method="post">
            <div class="form-group-loove">
                <label for="email" class="form-label-loove">Adresse email</label>
                <input type="email" name="email" id="email" 
                       class="form-control-loove <?php echo (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>" 
                       value="<?php echo htmlspecialchars($data['email'] ?? ''); ?>" 
                       placeholder="votre@email.com" required>
                <?php if(!empty($data['email_err'])): ?>
                    <span class="invalid-feedback"><?php echo $data['email_err']; ?></span>
                <?php endif; ?>
            </div>
            
            <div class="form-group-loove">
                <label for="password" class="form-label-loove">Mot de passe</label>
                <input type="password" id="password" name="password" 
                       class="form-control-loove <?php echo (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>" 
                       placeholder="••••••••••" required>
                <?php if(!empty($data['password_err'])): ?>
                    <span class="invalid-feedback"><?php echo $data['password_err']; ?></span>
                <?php endif; ?>
                <div class="forgot-password-link">
                    <a href="<?php echo BASEURL; ?>/users/forgotPassword">Mot de passe oublié ?</a>
                </div>
            </div>
            
            <div class="form-group-loove" style="display: flex; align-items: center;">
                <input type="checkbox" id="remember" name="remember" style="margin-right: 0.5rem;">
                <label for="remember" style="margin-bottom: 0; font-size: 0.9rem;">Se souvenir de moi</label>
            </div>
            
            <button type="submit" class="btn-loove btn-loove-primary btn-block" style="width: 100%;">
                <i class="fas fa-sign-in-alt"></i> Se connecter
            </button>
        </form>
        
        <p class="auth-switch-loove">
            Pas encore de compte ? <a href="<?php echo BASEURL; ?>/users/register">Inscrivez-vous</a>
        </p>
    </div>
</section>

<?php require APPROOT . '/views/includes/footer.php'; ?>
            <button class="close-btn-loove">&times;</button>
        </div>
        <div class="modal-body-loove text-center">
            <div class="welcome-animation">
                <i class="fas fa-check-circle" style="font-size: 4rem; color: var(--color-secondary); margin-bottom: 1rem;"></i>
            </div>
            <h3>Votre inscription a bien été prise en compte</h3>
            <p>Connectez-vous maintenant pour commencer à explorer des profils et trouver l'amour !</p>
            <div class="welcome-steps">
                <div class="welcome-step">
                    <div class="step-number">1</div>
                    <div class="step-text">Complétez votre profil</div>
                </div>
                <div class="welcome-step">
                    <div class="step-number">2</div>
                    <div class="step-text">Découvrez des profils</div>
                </div>
                <div class="welcome-step">
                    <div class="step-number">3</div>
                    <div class="step-text">Matchés et discutez</div>
                </div>
            </div>
        </div>
        <div class="modal-footer-loove">
            <button class="btn-loove btn-loove-primary" id="startJourneyBtn">Commencer l'aventure</button>
        </div>
    </div>
</div>

<style>
    /* Styles pour la modal de bienvenue */
    .modal-loove {
        display: none;
        position: fixed;
        z-index: 9999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        animation: fadeIn 0.3s ease-out;
    }
    
    .modal-content-loove {
        background-color: var(--color-surface);
        margin: 10% auto;
        width: 90%;
        max-width: 500px;
        border-radius: var(--border-radius-lg);
        box-shadow: var(--shadow-lg);
        overflow: hidden;
        animation: slideDown 0.4s ease-out;
    }
    
    .welcome-modal {
        max-width: 550px;
    }
    
    .modal-header-loove {
        padding: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid var(--color-divider);
    }
    
    .modal-header-loove h2 {
        margin: 0;
        font-size: 1.5rem;
    }
    
    .close-btn-loove {
        background: transparent;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: var(--color-text-secondary);
    }
    
    .modal-body-loove {
        padding: 2rem;
    }
    
    .modal-footer-loove {
        padding: 1.5rem;
        display: flex;
        justify-content: flex-end;
        border-top: 1px solid var(--color-divider);
    }
    
    .text-center {
        text-align: center;
    }
    
    .welcome-animation {
        margin-bottom: 1.5rem;
    }
    
    .welcome-steps {
        display: flex;
        justify-content: space-between;
        margin: 2rem 0;
    }
    
    .welcome-step {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 0 0.5rem;
    }
    
    .step-number {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background-color: var(--color-primary);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        margin-bottom: 0.5rem;
    }
    
    .step-text {
        text-align: center;
        font-size: 0.9rem;
    }
    
    .forgot-password-link {
        text-align: right;
        margin-top: 0.25rem;
        font-size: 0.85rem;
    }
    
    .forgot-password-link a {
        color: var(--color-primary);
        text-decoration: none;
    }
    
    .forgot-password-link a:hover {
        text-decoration: underline;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    @keyframes slideDown {
        from { transform: translateY(-50px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.1); }
        100% { transform: scale(1); }
    }
    
    .welcome-animation i {
        animation: pulse 1.5s infinite;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Vérifier si on doit afficher la modal de bienvenue
        <?php if(isset($_SESSION['registration_success']) && $_SESSION['registration_success']) : ?>
            const welcomeModal = document.getElementById('welcomeModal');
            const closeBtn = welcomeModal.querySelector('.close-btn-loove');
            const startJourneyBtn = document.getElementById('startJourneyBtn');
            
            // Afficher la modal
            welcomeModal.style.display = 'block';
            
            // Fermer la modal avec le bouton de fermeture
            closeBtn.addEventListener('click', function() {
                welcomeModal.style.display = 'none';
            });
            
            // Fermer la modal avec le bouton "Commencer l'aventure"
            startJourneyBtn.addEventListener('click', function() {
                welcomeModal.style.display = 'none';
            });
            
            // Fermer la modal si on clique en dehors
            window.addEventListener('click', function(event) {
                if (event.target === welcomeModal) {
                    welcomeModal.style.display = 'none';
                }
            });
            
            // Supprimer le flag de session une fois la modal affichée
            <?php unset($_SESSION['registration_success']); ?>
        <?php endif; ?>
    });
</script>

<?php require APPROOT . '/views/includes/footer.php'; ?>
