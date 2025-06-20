<?php require APPROOT . '/views/includes/header.php'; ?>

<div class="auth-container-loove">
    <div class="auth-card-loove slide-up" style="max-width: 600px;">
        <div style="text-align: center; margin-bottom: 2rem;">
            <a class="navbar-brand-loove" href="<?php echo BASEURL; ?>" style="font-size: 2.5rem; display: inline-block;">
                <i class="fas fa-heart"></i>Loove
            </a>
        </div>
        
        <h2>Créer un compte</h2>
        <p class="auth-subtitle">Rejoignez notre communauté et commencez votre voyage</p>

        <form action="<?php echo BASEURL; ?>/users/register" method="post">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1rem;">
                <div class="form-group-loove">
                    <label for="first_name" class="form-label-loove">Prénom</label>
                    <input type="text" name="first_name" id="first_name"
                           class="form-control-loove <?php echo (!empty($data['first_name_err'])) ? 'is-invalid' : ''; ?>"
                           value="<?php echo htmlspecialchars($data['first_name'] ?? ''); ?>" 
                           placeholder="Votre prénom" required>
                    <?php if(!empty($data['first_name_err'])): ?>
                        <span class="invalid-feedback"><?php echo $data['first_name_err']; ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group-loove">
                    <label for="last_name" class="form-label-loove">Nom</label>
                    <input type="text" name="last_name" id="last_name"
                           class="form-control-loove <?php echo (!empty($data['last_name_err'])) ? 'is-invalid' : ''; ?>"
                           value="<?php echo htmlspecialchars($data['last_name'] ?? ''); ?>" 
                           placeholder="Votre nom" required>
                    <?php if(!empty($data['last_name_err'])): ?>
                        <span class="invalid-feedback"><?php echo $data['last_name_err']; ?></span>
                    <?php endif; ?>
                </div>
            </div>

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
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1rem;">
                <div class="form-group-loove">
                    <label for="gender" class="form-label-loove">Genre</label>
                    <select name="gender" id="gender" 
                            class="form-control-loove <?php echo (!empty($data['gender_err'])) ? 'is-invalid' : ''; ?>" required>
                        <option value="">Sélectionnez...</option>
                        <option value="homme" <?php echo (isset($data['gender']) && $data['gender'] == 'homme') ? 'selected' : ''; ?>>Homme</option>
                        <option value="femme" <?php echo (isset($data['gender']) && $data['gender'] == 'femme') ? 'selected' : ''; ?>>Femme</option>
                        <option value="non-binaire" <?php echo (isset($data['gender']) && $data['gender'] == 'non-binaire') ? 'selected' : ''; ?>>Non-binaire</option>
                        <option value="autre" <?php echo (isset($data['gender']) && $data['gender'] == 'autre') ? 'selected' : ''; ?>>Autre</option>
                    </select>
                    <?php if(!empty($data['gender_err'])): ?>
                        <span class="invalid-feedback"><?php echo $data['gender_err']; ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group-loove">
                    <label for="birth_date" class="form-label-loove">Date de naissance</label>
                    <input type="date" name="birth_date" id="birth_date"
                           class="form-control-loove <?php echo (!empty($data['birth_date_err'])) ? 'is-invalid' : ''; ?>"
                           value="<?php echo htmlspecialchars($data['birth_date'] ?? ''); ?>" required>
                    <?php if(!empty($data['birth_date_err'])): ?>
                        <span class="invalid-feedback"><?php echo $data['birth_date_err']; ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1rem;">
                <div class="form-group-loove">
                    <label for="password" class="form-label-loove">Mot de passe</label>
                    <input type="password" name="password" id="password"
                           class="form-control-loove <?php echo (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>"
                           placeholder="Minimum 6 caractères" required>
                    <?php if(!empty($data['password_err'])): ?>
                        <span class="invalid-feedback"><?php echo $data['password_err']; ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group-loove">
                    <label for="confirm_password" class="form-label-loove">Confirmer mot de passe</label>
                    <input type="password" name="confirm_password" id="confirm_password"
                           class="form-control-loove <?php echo (!empty($data['confirm_password_err'])) ? 'is-invalid' : ''; ?>"
                           placeholder="Retapez votre mot de passe" required>
                    <?php if(!empty($data['confirm_password_err'])): ?>
                        <span class="invalid-feedback"><?php echo $data['confirm_password_err']; ?></span>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="form-group-loove" style="display: flex; align-items: start; margin-top: 0.5rem;">
                <input type="checkbox" id="terms" name="terms" style="margin-right: 0.5rem; margin-top: 0.25rem;" required>
                <label for="terms" style="margin-bottom: 0; font-size: 0.9rem; line-height: 1.4;">
                    J'accepte les <a href="#">conditions d'utilisation</a> et la <a href="#">politique de confidentialité</a> de Loove.
                </label>
            </div>
            
            <button type="submit" class="btn-loove btn-loove-primary" style="width: 100%; margin-top: 1rem;">
                <i class="fas fa-user-plus"></i> Créer mon compte
            </button>
        </form>
        
        <p class="auth-switch-loove">
            Vous avez déjà un compte ? <a href="<?php echo BASEURL; ?>/users/login">Connectez-vous</a>
        </p>
    </div>
</div>

<?php require APPROOT . '/views/includes/footer.php'; ?>
