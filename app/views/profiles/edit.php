<?php require APPROOT . '/views/includes/header.php'; ?>

<section class="profile-edit-loove-section" style="padding: 2rem 0;">
    <div style="text-align: center; margin-bottom: 3rem;">
        <h1 class="section-title-loove" style="font-size: 2.8rem;"><?php echo htmlspecialchars($data['title']); ?></h1>
        <p class="section-subtitle-loove" style="font-size: 1.1rem;">Mettez à jour vos informations pour de meilleures connexions.</p>
    </div>

    <?php flash('profile_message'); ?>

    <div class="auth-card-loove" style="max-width: 800px; margin: auto;"> <!-- Réutilisation du style de carte d'authentification pour la cohérence -->
        <form action="<?php echo BASEURL; ?>/profiles/edit/<?php echo $data['user_id']; ?>" method="post" enctype="multipart/form-data">
            
            <div style="text-align: center; margin-bottom: 2rem;">
                <img src="<?php echo BASEURL; ?>/img/profiles/<?php echo htmlspecialchars(!empty($data['profile_pic_current']) ? $data['profile_pic_current'] : 'default.jpg'); ?>" 
                     alt="Photo de profil actuelle" 
                     style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover; border: 4px solid var(--color-bg-light); box-shadow: var(--shadow-raised); margin-bottom: 1rem;">
                <input type="hidden" name="profile_pic_current" value="<?php echo htmlspecialchars($data['profile_pic_current']); ?>">
            </div>

            <div class="form-group-loove">
                <label for="profile_pic" class="form-label-loove">Changer de photo de profil</label>
                <input type="file" name="profile_pic" id="profile_pic" class="form-control-loove" style="padding: 0.6rem;">
                <?php if(!empty($data['profile_pic_err'])): ?>
                    <span class="invalid-feedback"><?php echo $data['profile_pic_err']; ?></span>
                <?php endif; ?>
            </div>

            <div class="row" style="display: flex; gap: 1.5rem; margin-bottom: 1.5rem;">
                <div class="form-group-loove" style="flex: 1;">
                    <label for="first_name" class="form-label-loove">Prénom</label>
                    <input type="text" name="first_name" id="first_name"
                           class="form-control-loove <?php echo (!empty($data['first_name_err'])) ? 'is-invalid' : ''; ?>"
                           value="<?php echo htmlspecialchars($data['first_name']); ?>" required>
                    <?php if(!empty($data['first_name_err'])): ?>
                        <span class="invalid-feedback"><?php echo $data['first_name_err']; ?></span>
                    <?php endif; ?>
                </div>
                <div class="form-group-loove" style="flex: 1;">
                    <label for="last_name" class="form-label-loove">Nom</label>
                    <input type="text" name="last_name" id="last_name"
                           class="form-control-loove <?php echo (!empty($data['last_name_err'])) ? 'is-invalid' : ''; ?>"
                           value="<?php echo htmlspecialchars($data['last_name']); ?>" required>
                    <?php if(!empty($data['last_name_err'])): ?>
                        <span class="invalid-feedback"><?php echo $data['last_name_err']; ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-group-loove">
                <label for="email" class="form-label-loove">Adresse Email (non modifiable)</label>
                <input type="email" name="email_display" id="email_display"
                       class="form-control-loove"
                       value="<?php echo htmlspecialchars($data['email']); ?>" disabled>
            </div>
            
            <div class="form-group-loove">
                <label for="bio" class="form-label-loove">Ma biographie</label>
                <textarea name="bio" id="bio" rows="5"
                          class="form-control-loove <?php echo (!empty($data['bio_err'])) ? 'is-invalid' : ''; ?>"
                          placeholder="Parlez un peu de vous, vos passions, ce que vous recherchez..."><?php echo htmlspecialchars($data['bio']); ?></textarea>
                <?php if(!empty($data['bio_err'])): ?>
                    <span class="invalid-feedback"><?php echo $data['bio_err']; ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group-loove">
                <label for="location" class="form-label-loove">Localisation</label>
                <input type="text" name="location" id="location"
                       class="form-control-loove <?php echo (!empty($data['location_err'])) ? 'is-invalid' : ''; ?>"
                       value="<?php echo htmlspecialchars($data['location']); ?>" placeholder="Ex: Paris, France">
                <?php if(!empty($data['location_err'])): ?>
                    <span class="invalid-feedback"><?php echo $data['location_err']; ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group-loove">
                <label for="relationship_type" class="form-label-loove">Type de relation recherché</label>
                <select name="relationship_type" id="relationship_type" class="form-control-loove">
                    <option value="">Non spécifié</option>
                    <option value="amitié" <?php echo ($data['relationship_type'] == 'amitié') ? 'selected' : ''; ?>>Amitié</option>
                    <option value="casual" <?php echo ($data['relationship_type'] == 'casual') ? 'selected' : ''; ?>>Relation décontractée</option>
                    <option value="sérieux" <?php echo ($data['relationship_type'] == 'sérieux') ? 'selected' : ''; ?>>Relation sérieuse</option>
                    <option value="mariage" <?php echo ($data['relationship_type'] == 'mariage') ? 'selected' : ''; ?>>Mariage</option>
                </select>
            </div>
            
            <button type="submit" class="btn-loove btn-loove-primary" style="width: 100%; margin-top: 1rem;"><i class="fas fa-save"></i> Enregistrer les modifications</button>
        </form>
    </div>
</section>

<?php require APPROOT . '/views/includes/footer.php'; ?>
