<?php require APPROOT . '/views/includes/header.php'; ?>

<div class="auth-container slide-up">
    <div class="auth-card">
        <div class="auth-header">
            <h2><i class="fas fa-lock"></i> Réinitialiser votre mot de passe</h2>
            <p>Créez un nouveau mot de passe pour votre compte</p>
        </div>
        
        <?php flash('reset_error'); ?>
        
        <form action="<?php echo BASEURL; ?>/users/resetPassword/<?php echo $data['token']; ?>" method="POST" class="auth-form">
            <div class="form-group-loove">
                <div class="user-email-display">
                    <span>Compte : </span>
                    <strong><?php echo $data['token_data']->email; ?></strong>
                </div>
            </div>
            
            <div class="form-group-loove">
                <label for="password" class="form-label-loove">Nouveau mot de passe</label>
                <input type="password" id="password" name="password" class="form-control-loove <?php echo (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['password']; ?>" placeholder="Entrez votre nouveau mot de passe">
                <span class="invalid-feedback"><?php echo $data['password_err']; ?></span>
            </div>
            
            <div class="form-group-loove">
                <label for="confirm_password" class="form-label-loove">Confirmer le mot de passe</label>
                <input type="password" id="confirm_password" name="confirm_password" class="form-control-loove <?php echo (!empty($data['confirm_password_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['confirm_password']; ?>" placeholder="Confirmez votre mot de passe">
                <span class="invalid-feedback"><?php echo $data['confirm_password_err']; ?></span>
            </div>
            
            <div class="password-strength-container">
                <div class="password-strength-label">Force du mot de passe : <span id="strength-text">Faible</span></div>
                <div class="password-strength-meter">
                    <div class="strength-bar" id="strength-bar"></div>
                </div>
                <div class="password-requirements">
                    <div class="requirement" id="req-length"><i class="fas fa-times"></i> Au moins 6 caractères</div>
                    <div class="requirement" id="req-uppercase"><i class="fas fa-times"></i> Au moins 1 majuscule</div>
                    <div class="requirement" id="req-lowercase"><i class="fas fa-times"></i> Au moins 1 minuscule</div>
                    <div class="requirement" id="req-number"><i class="fas fa-times"></i> Au moins 1 chiffre</div>
                </div>
            </div>
            
            <div class="form-group-loove">
                <button type="submit" class="btn-loove btn-loove-primary btn-block">
                    <i class="fas fa-save"></i> Réinitialiser mon mot de passe
                </button>
            </div>
        </form>
        
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
    .user-email-display {
        background-color: var(--color-surface-variant);
        padding: 1rem;
        border-radius: var(--border-radius-md);
        margin-bottom: 1.5rem;
        color: var(--color-text-secondary);
    }
    
    .user-email-display strong {
        color: var(--color-text-primary);
    }
    
    .password-strength-container {
        margin-bottom: 1.5rem;
    }
    
    .password-strength-label {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
        color: var(--color-text-secondary);
    }
    
    .password-strength-meter {
        height: 8px;
        background-color: var(--color-surface-variant);
        border-radius: var(--border-radius-full);
        overflow: hidden;
    }
    
    .strength-bar {
        height: 100%;
        width: 0%;
        transition: width 0.3s, background-color 0.3s;
    }
    
    .password-requirements {
        margin-top: 1rem;
        font-size: 0.85rem;
    }
    
    .requirement {
        margin-bottom: 0.5rem;
        color: var(--color-text-secondary);
    }
    
    .requirement i {
        margin-right: 0.5rem;
        color: var(--color-error);
    }
    
    .requirement.valid i {
        color: var(--color-secondary);
    }
    
    .requirement.valid {
        color: var(--color-text-primary);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const passwordInput = document.getElementById('password');
        const strengthBar = document.getElementById('strength-bar');
        const strengthText = document.getElementById('strength-text');
        const reqLength = document.getElementById('req-length');
        const reqUppercase = document.getElementById('req-uppercase');
        const reqLowercase = document.getElementById('req-lowercase');
        const reqNumber = document.getElementById('req-number');
        
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            let strength = 0;
            
            // Vérifier la longueur
            if(password.length >= 6) {
                strength += 25;
                reqLength.classList.add('valid');
                reqLength.querySelector('i').className = 'fas fa-check';
            } else {
                reqLength.classList.remove('valid');
                reqLength.querySelector('i').className = 'fas fa-times';
            }
            
            // Vérifier les majuscules
            if(/[A-Z]/.test(password)) {
                strength += 25;
                reqUppercase.classList.add('valid');
                reqUppercase.querySelector('i').className = 'fas fa-check';
            } else {
                reqUppercase.classList.remove('valid');
                reqUppercase.querySelector('i').className = 'fas fa-times';
            }
            
            // Vérifier les minuscules
            if(/[a-z]/.test(password)) {
                strength += 25;
                reqLowercase.classList.add('valid');
                reqLowercase.querySelector('i').className = 'fas fa-check';
            } else {
                reqLowercase.classList.remove('valid');
                reqLowercase.querySelector('i').className = 'fas fa-times';
            }
            
            // Vérifier les chiffres
            if(/[0-9]/.test(password)) {
                strength += 25;
                reqNumber.classList.add('valid');
                reqNumber.querySelector('i').className = 'fas fa-check';
            } else {
                reqNumber.classList.remove('valid');
                reqNumber.querySelector('i').className = 'fas fa-times';
            }
            
            // Mettre à jour la barre de force
            strengthBar.style.width = strength + '%';
            
            // Changer la couleur et le texte selon la force
            if(strength <= 25) {
                strengthBar.style.backgroundColor = '#ff4d4d';
                strengthText.textContent = 'Faible';
                strengthText.style.color = '#ff4d4d';
            } else if(strength <= 50) {
                strengthBar.style.backgroundColor = '#ffa64d';
                strengthText.textContent = 'Moyen';
                strengthText.style.color = '#ffa64d';
            } else if(strength <= 75) {
                strengthBar.style.backgroundColor = '#4da6ff';
                strengthText.textContent = 'Bon';
                strengthText.style.color = '#4da6ff';
            } else {
                strengthBar.style.backgroundColor = '#4dff4d';
                strengthText.textContent = 'Fort';
                strengthText.style.color = '#4dff4d';
            }
        });
    });
</script>

<?php require APPROOT . '/views/includes/footer.php'; ?>
