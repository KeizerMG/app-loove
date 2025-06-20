<?php require APPROOT . '/views/includes/header.php'; ?>

<div class="auth-container slide-up">
    <div class="auth-card">
        <div class="auth-header">
            <div class="success-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h2>Lien de réinitialisation créé</h2>
            <p>Un lien de réinitialisation a été généré.</p>
        </div>
        
        <div class="success-message">
            <p>Dans un environnement de production, un email serait envoyé à votre adresse avec ce lien.</p>
            <p>Pour cette démonstration, voici le lien de réinitialisation :</p>
            
            <div class="reset-link-container">
                <input type="text" class="form-control-loove" value="<?php echo $data['reset_link']; ?>" readonly id="resetLink">
                <button class="btn-loove btn-loove-outline" onclick="copyResetLink()">
                    <i class="fas fa-copy"></i>
                </button>
            </div>
            <div id="copySuccess" class="copy-success">Lien copié !</div>
            
            <div class="link-direct">
                <a href="<?php echo $data['reset_link']; ?>" class="btn-loove btn-loove-primary btn-block">
                    <i class="fas fa-link"></i> Ouvrir le lien de réinitialisation
                </a>
            </div>
        </div>
        
        <div class="auth-footer">
            <div class="auth-links">
                <a href="<?php echo BASEURL; ?>/users/login">
                    <i class="fas fa-arrow-left"></i> Retour à la connexion
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    .success-icon {
        text-align: center;
        margin-bottom: 1.5rem;
    }
    
    .success-icon i {
        font-size: 4rem;
        color: var(--color-secondary);
        animation: scaleIn 0.5s ease-out;
    }
    
    .success-message {
        background-color: var(--color-surface-variant);
        padding: 1.5rem;
        border-radius: var(--border-radius-md);
        margin-bottom: 1.5rem;
    }
    
    .reset-link-container {
        display: flex;
        margin: 1rem 0;
    }
    
    .reset-link-container input {
        flex: 1;
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
        font-size: 0.9rem;
        color: var(--color-text-secondary);
    }
    
    .reset-link-container button {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
    }
    
    .copy-success {
        color: var(--color-secondary);
        font-size: 0.9rem;
        margin-bottom: 1rem;
        display: none;
    }
    
    .link-direct {
        margin-top: 1.5rem;
    }
    
    @keyframes scaleIn {
        0% { transform: scale(0); opacity: 0; }
        100% { transform: scale(1); opacity: 1; }
    }
</style>

<script>
    function copyResetLink() {
        const resetLink = document.getElementById('resetLink');
        resetLink.select();
        document.execCommand('copy');
        
        const copySuccess = document.getElementById('copySuccess');
        copySuccess.style.display = 'block';
        
        setTimeout(() => {
            copySuccess.style.display = 'none';
        }, 3000);
    }
</script>

<?php require APPROOT . '/views/includes/footer.php'; ?>
