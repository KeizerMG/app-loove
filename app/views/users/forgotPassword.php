<?php require APPROOT . '/views/includes/header.php'; ?>

<div class="auth-container slide-up">
    <div class="auth-card">
        <div class="auth-header">
            <h2><i class="fas fa-key"></i> Mot de passe oublié</h2>
            <p>Entrez votre adresse e-mail pour recevoir un lien de réinitialisation</p>
        </div>
        
        <?php flash('forgot_password_error'); ?>
        
        <form action="<?php echo BASEURL; ?>/users/forgotPassword" method="POST" class="auth-form">
            <div class="form-group-loove">
                <label for="email" class="form-label-loove">Adresse e-mail</label>
                <input type="email" id="email" name="email" class="form-control-loove <?php echo (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['email']; ?>" placeholder="Entrez votre adresse e-mail">
                <span class="invalid-feedback"><?php echo $data['email_err']; ?></span>
            </div>
            
            <div class="form-group-loove">
                <button type="submit" class="btn-loove btn-loove-primary btn-block">
                    <i class="fas fa-paper-plane"></i> Envoyer le lien de réinitialisation
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

<?php require APPROOT . '/views/includes/footer.php'; ?>
