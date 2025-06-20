<?php require APPROOT . '/views/includes/header.php'; ?>

<?php if(!isLoggedIn()): ?>
<section class="hero-loove" style="display: flex; align-items: center; justify-content: space-between; gap: 3rem;">
    <div class="hero-content-loove slide-up">
        <h1>Découvrez des connexions <span style="display: block;">authentiques</span></h1>
        <p>Loove est une plateforme de rencontre innovante qui met l'accent sur les relations sincères et les affinités réelles. Nous utilisons une technologie avancée pour vous connecter avec des personnes qui vous correspondent vraiment.</p>
        <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
            <a href="<?php echo BASEURL; ?>/users/register" class="btn-loove btn-loove-primary">
                <i class="fas fa-rocket"></i> Commencer maintenant
            </a>
            <a href="<?php echo BASEURL; ?>/pages/about" class="btn-loove btn-loove-outline">
                <i class="fas fa-info-circle"></i> En savoir plus
            </a>
        </div>
    </div>
    <div class="hero-image-loove slide-up delay-2" style="max-width: 500px;">
        <img src="<?php echo BASEURL; ?>/img/hero-image.jpg" alt="Couple utilisant Loove" style="width: 100%; border-radius: var(--border-radius-lg);">
    </div>
</section>

<section class="features-loove">
    <h2 class="section-title-loove fade-in">Pourquoi choisir Loove ?</h2>
    <p class="section-subtitle-loove fade-in delay-1">Notre plateforme est conçue pour créer des connexions significatives qui vont au-delà de la première impression.</p>
    
    <div class="features-grid-loove">
        <div class="feature-card-loove slide-up delay-2">
            <div class="feature-icon-loove">
                <i class="fas fa-shield-alt"></i>
            </div>
            <h3>Sécurité et confiance</h3>
            <p>Des profils vérifiés et une communauté bienveillante pour des rencontres en toute sérénité.</p>
        </div>
        
        <div class="feature-card-loove slide-up delay-3">
            <div class="feature-icon-loove">
                <i class="fas fa-chart-line"></i>
            </div>
            <h3>Compatibilité avancée</h3>
            <p>Notre algorithme sophistiqué vous propose des matchs qui correspondent réellement à vos attentes.</p>
        </div>
        
        <div class="feature-card-loove slide-up delay-4">
            <div class="feature-icon-loove">
                <i class="fas fa-comments"></i>
            </div>
            <h3>Communication fluide</h3>
            <p>Des outils de messagerie intuitifs pour entamer des conversations naturelles et engageantes.</p>
        </div>
    </div>
</section>

<section style="padding: 5rem 0; text-align: center;">
    <div style="max-width: 800px; margin: 0 auto;">
        <h2 class="section-title-loove fade-in">Prêt à trouver l'amour ?</h2>
        <p class="section-subtitle-loove fade-in delay-1">Rejoignez Loove et connectez-vous avec des personnes qui partagent vos valeurs et vos passions.</p>
        <a href="<?php echo BASEURL; ?>/users/register" class="btn-loove btn-loove-tertiary slide-up delay-2" style="font-size: 1.1rem; padding: 1rem 2.5rem;">
            <i class="fas fa-heart"></i> Créer mon compte
        </a>
    </div>
</section>

<?php else: ?>
    <div style="padding: 3rem 0; text-align: center;" class="slide-up">
        <h1 class="section-title-loove" style="margin-bottom: 2rem;">Bienvenue, <?php 
            if (isset($_SESSION['user_name']) && !empty($_SESSION['user_name'])) {
                echo htmlspecialchars(explode(' ', $_SESSION['user_name'])[0]); 
            } else {
                echo "Utilisateur"; 
            }
        ?> !</h1>
        <p style="color: var(--color-text-secondary); font-size: 1.2rem; max-width: 600px; margin: 0 auto 3rem;">Découvrez de nouvelles personnes, engagez des conversations et créez des connexions significatives.</p>
        
        <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 1.5rem; margin-bottom: 4rem;">
            <a href="<?php echo BASEURL; ?>/discover" class="btn-loove btn-loove-primary" style="padding: 1.2rem 2rem;">
                <i class="fas fa-compass fa-lg"></i>
                <span style="display: block; font-size: 1.1rem; margin-top: 0.5rem;">Explorer</span>
            </a>
            <a href="<?php echo BASEURL; ?>/messages" class="btn-loove btn-loove-secondary" style="padding: 1.2rem 2rem;">
                <i class="fas fa-comment-dots fa-lg"></i>
                <span style="display: block; font-size: 1.1rem; margin-top: 0.5rem;">Messages</span>
            </a>
            <a href="<?php echo BASEURL; ?>/profiles/edit/<?php echo $_SESSION['user_id']; ?>" class="btn-loove btn-loove-tertiary" style="padding: 1.2rem 2rem;">
                <i class="fas fa-user fa-lg"></i>
                <span style="display: block; font-size: 1.1rem; margin-top: 0.5rem;">Mon Profil</span>
            </a>
        </div>
        
        <div class="card-loove" style="max-width: 800px; margin: 0 auto; text-align: left; padding: 1.5rem;">
            <h3 style="margin-bottom: 1rem; color: var(--color-primary);">Conseils pour maximiser vos rencontres :</h3>
            <ul style="list-style: none; padding: 0; color: var(--color-text-secondary); margin-bottom: 0;">
                <li style="margin-bottom: 0.75rem; display: flex; align-items: start;">
                    <i class="fas fa-check-circle" style="color: var(--color-success); margin-right: 0.75rem; margin-top: 0.25rem;"></i>
                    <span>Complétez votre profil avec une bio attrayante et des photos récentes</span>
                </li>
                <li style="margin-bottom: 0.75rem; display: flex; align-items: start;">
                    <i class="fas fa-check-circle" style="color: var(--color-success); margin-right: 0.75rem; margin-top: 0.25rem;"></i>
                    <span>Soyez ouvert et authentique dans vos conversations</span>
                </li>
                <li style="display: flex; align-items: start;">
                    <i class="fas fa-check-circle" style="color: var(--color-success); margin-right: 0.75rem; margin-top: 0.25rem;"></i>
                    <span>Consultez régulièrement l'application pour découvrir de nouveaux profils</span>
                </li>
            </ul>
        </div>
    </div>
<?php endif; ?>

<?php require APPROOT . '/views/includes/footer.php'; ?>
