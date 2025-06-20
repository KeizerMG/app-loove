<?php require APPROOT . '/views/includes/header.php'; ?>

<section class="about-loove-section" style="padding: 3rem 0;">
    <div class="container-loove-narrow" style="max-width: 900px; margin: auto; text-align: center;">
        
        <h1 class="section-title-loove" style="font-size: 3rem; margin-bottom: 1.5rem;"><?php echo htmlspecialchars($data['title']); ?></h1>
        
        <p class="section-subtitle-loove" style="font-size: 1.2rem; color: var(--color-text-medium); margin-bottom: 3rem;">
            <?php echo htmlspecialchars($data['description']); ?>
        </p>

        <div class="about-content" style="text-align: left; line-height: 1.8; color: var(--color-text-medium);">
            <p style="margin-bottom: 1.5rem;">
                Chez Loove, notre mission est de transcender les rencontres en ligne traditionnelles. Nous croyons en la puissance des connexions authentiques, basées sur des valeurs partagées, des passions communes et une véritable compatibilité. Fatigué(e) des swipes superficiels ? Nous aussi. C'est pourquoi nous avons développé une plateforme où chaque interaction compte, où chaque profil raconte une histoire unique.
            </p>
            
            <h3 style="font-family: var(--font-heading); font-size: 1.8rem; color: var(--color-text-light); margin-top: 3rem; margin-bottom: 1rem;">Notre Vision</h3>
            <p style="margin-bottom: 1.5rem;">
                Nous aspirons à être plus qu'une simple application de rencontre. Loove se veut être un espace de confiance et d'inspiration, où les célibataires peuvent explorer, se découvrir et, qui sait, trouver la personne qui fera battre leur cœur un peu plus fort. Nous utilisons une technologie de matching intelligente, mais nous n'oublions jamais que derrière chaque écran, il y a un être humain avec ses rêves et ses espoirs.
            </p>

            <h3 style="font-family: var(--font-heading); font-size: 1.8rem; color: var(--color-text-light); margin-top: 3rem; margin-bottom: 1rem;">Nos Engagements</h3>
            <ul style="list-style: none; padding-left: 0; margin-bottom: 1.5rem;">
                <li style="margin-bottom: 1rem; display: flex; align-items: flex-start;">
                    <i class="fas fa-check-circle" style="color: var(--color-primary-accent); margin-right: 0.75rem; font-size: 1.2rem; margin-top: 0.2rem;"></i>
                    <span><strong>Authenticité :</strong> Encourager des profils sincères et des interactions véritables.</span>
                </li>
                <li style="margin-bottom: 1rem; display: flex; align-items: flex-start;">
                    <i class="fas fa-shield-alt" style="color: var(--color-primary-accent); margin-right: 0.75rem; font-size: 1.2rem; margin-top: 0.2rem;"></i>
                    <span><strong>Sécurité :</strong> Prioriser la protection de vos données et la sécurité de notre communauté.</span>
                </li>
                <li style="margin-bottom: 1rem; display: flex; align-items: flex-start;">
                    <i class="fas fa-lightbulb" style="color: var(--color-primary-accent); margin-right: 0.75rem; font-size: 1.2rem; margin-top: 0.2rem;"></i>
                    <span><strong>Innovation :</strong> Améliorer constamment notre plateforme avec des fonctionnalités utiles et intuitives.</span>
                </li>
                <li style="margin-bottom: 1rem; display: flex; align-items: flex-start;">
                    <i class="fas fa-users" style="color: var(--color-primary-accent); margin-right: 0.75rem; font-size: 1.2rem; margin-top: 0.2rem;"></i>
                    <span><strong>Communauté :</strong> Bâtir un espace respectueux et inclusif pour tous nos membres.</span>
                </li>
            </ul>

            <p style="margin-top: 3rem; font-size: 1.1rem;">
                Merci de faire partie de l'aventure Loove. Ensemble, créons des histoires d'amour mémorables !
            </p>
        </div>

        <div style="margin-top: 4rem;">
            <a href="<?php echo BASEURL; ?>/users/register" class="btn-loove btn-loove-primary"><i class="fas fa-heart-pulse"></i> Rejoindre Loove maintenant</a>
        </div>

    </div>
</section>

<?php require APPROOT . '/views/includes/footer.php'; ?>
