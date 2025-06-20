</main> <!-- Fin du container-loove -->
    
    <footer class="footer-loove">
        <a href="<?php echo BASEURL; ?>" class="logo-footer"><i class="fas fa-heart"></i> Loove</a>
        <p>Découvrez des connexions authentiques dans un environnement sécurisé.</p>
        <div class="footer-links-loove">
            <a href="<?php echo BASEURL; ?>/pages/privacy">Confidentialité</a>
            <span style="margin: 0 0.5rem; color: var(--color-text-tertiary);">&bull;</span>
            <a href="<?php echo BASEURL; ?>/pages/terms">Conditions</a>
            <span style="margin: 0 0.5rem; color: var(--color-text-tertiary);">&bull;</span>
            <a href="<?php echo BASEURL; ?>/pages/contact">Contact</a>
        </div>
        <p style="margin-top: 1.5rem; opacity: 0.7;">&copy; <?php echo date('Y'); ?> <?php echo SITENAME; ?>. Tous droits réservés.</p>
    </footer>
    
    <script>
    // Animation pour les éléments avec les classes fade-in et slide-up
    document.addEventListener('DOMContentLoaded', function() {
        // Fonction pour animer les éléments lorsqu'ils entrent dans la viewport
        function animateOnScroll() {
            const elements = document.querySelectorAll('.fade-in, .slide-up');
            
            elements.forEach(element => {
                const position = element.getBoundingClientRect();
                if(position.top < window.innerHeight && position.bottom >= 0) {
                    element.style.opacity = '1';
                    element.style.transform = 'translateY(0)';
                }
            });
        }
        
        // Initialiser les éléments à animer avec un style par défaut
        const elementsToAnimate = document.querySelectorAll('.fade-in, .slide-up');
        elementsToAnimate.forEach(element => {
            if(element.classList.contains('fade-in')) {
                element.style.opacity = '0';
                element.style.transition = 'opacity 0.5s ease-out';
            }
            if(element.classList.contains('slide-up')) {
                element.style.opacity = '0';
                element.style.transform = 'translateY(30px)';
                element.style.transition = 'opacity 0.5s ease-out, transform 0.5s ease-out';
            }
        });
        
        // Appliquer l'animation au chargement initial
        setTimeout(animateOnScroll, 100);
        
        // Appliquer l'animation lors du défilement
        window.addEventListener('scroll', animateOnScroll);
    });
    </script>
</body>
</html>
