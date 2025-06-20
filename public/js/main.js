document.addEventListener('DOMContentLoaded', function() {    const flashMessages = document.getElementById('msg-flash');
    if (flashMessages) {
        setTimeout(function() {
            flashMessages.style.opacity = '0';
            setTimeout(function() {
                flashMessages.remove();
            }, 500);
        }, 3000);
    }
    
    // Toggle password visibility
    const togglePassword = document.querySelector('.toggle-password');
    if (togglePassword) {
        togglePassword.addEventListener('click', function() {
            const passwordInput = document.querySelector(this.getAttribute('toggle'));
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                this.classList.add('fa-eye-slash');
                this.classList.remove('fa-eye');
            } else {
                passwordInput.type = 'password';
                this.classList.add('fa-eye');
                this.classList.remove('fa-eye-slash');
            }
        });
    }
    
    // Gestion du menu mobile navbar
    const navbarToggler = document.querySelector('.navbar-toggler-loove');
    const navbarMenu = document.getElementById('navbarMenu') || document.querySelector('.navbar-menu-container');
    
    if (navbarToggler && navbarMenu) {
        navbarToggler.addEventListener('click', function() {
            navbarMenu.classList.toggle('active');
            this.classList.toggle('active');
        });
    }
    
    // Toggle pour le dropdown de profil
    const profileToggles = document.querySelectorAll('.profile-toggle');
    profileToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            const dropdown = this.nextElementSibling;
            dropdown.classList.toggle('show');
            
            // Fermer les autres dropdowns ouverts
            document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
                if (menu !== dropdown) menu.classList.remove('show');
            });
        });
    });
    
    // Fermer les dropdowns quand on clique ailleurs
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.profile-dropdown')) {
            document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
                menu.classList.remove('show');
            });
        }
    });
    
    // Gestion du preloader
    document.addEventListener('DOMContentLoaded', function() {
        // Masquer le preloader après le chargement
        setTimeout(function() {
            const preloader = document.querySelector('.preloader');
            if (preloader) {
                preloader.classList.add('hidden');
            }
        }, 800);
        
        // Menu mobile toggle
        const navToggle = document.getElementById('navToggle');
        const navMenu = document.getElementById('navMenu');
        
        if (navToggle && navMenu) {
            navToggle.addEventListener('click', function() {
                navMenu.classList.toggle('active');
                this.classList.toggle('active');
            });
        }
        
        // Profile dropdown
        const profileToggle = document.getElementById('profileToggle');
        const profileDropdown = document.getElementById('profileDropdown');
        
        if (profileToggle && profileDropdown) {
            profileToggle.addEventListener('click', function(e) {
                e.preventDefault();
                profileDropdown.classList.toggle('show');
            });
            
            // Fermer le dropdown quand on clique ailleurs
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.profile-dropdown')) {
                    profileDropdown.classList.remove('show');
                }
            });
        }
        
        // Fermer le menu mobile quand on clique sur les liens
        const navLinks = document.querySelectorAll('.nav-link-loove');
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                if (navMenu) {
                    navMenu.classList.remove('active');
                }
                if (navToggle) {
                    navToggle.classList.remove('active');
                }
            });
        });
        
        // Animation des éléments au scroll
        const fadeElements = document.querySelectorAll('.fade-in, .slide-up');
        
        function checkFadeElements() {
            const triggerBottom = window.innerHeight * 0.8;
            
            fadeElements.forEach(element => {
                const elementTop = element.getBoundingClientRect().top;
                
                if (elementTop < triggerBottom) {
                    element.style.opacity = '1';
                    element.style.transform = 'translateY(0)';
                }
            });
        }
        
        // Vérifier les éléments au chargement et au scroll
        checkFadeElements();
        window.addEventListener('scroll', checkFadeElements);
        
        // Gestion des cartes au hover
        const cards = document.querySelectorAll('.card-loove, .feature-card-loove, .profile-card-discover');
        
        cards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-10px)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
        
        // Gestion des alertes dismissibles
        const alerts = document.querySelectorAll('.alert-loove');
        
        alerts.forEach(alert => {
            // Créer un bouton de fermeture s'il n'existe pas
            if (!alert.querySelector('.alert-close')) {
                const closeButton = document.createElement('button');
                closeButton.classList.add('alert-close');
                closeButton.innerHTML = '&times;';
                closeButton.style.cssText = `
                    position: absolute;
                    top: 10px;
                    right: 15px;
                    background: transparent;
                    border: none;
                    font-size: 1.2rem;
                    cursor: pointer;
                    color: inherit;
                    opacity: 0.7;
                `;
                
                alert.style.position = 'relative';
                alert.appendChild(closeButton);
                
                // Ajouter l'événement de fermeture
                closeButton.addEventListener('click', function() {
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateY(-20px)';
                    setTimeout(() => {
                        alert.style.display = 'none';
                    }, 300);
                });
            }
            
            // Fermer automatiquement après 5 secondes
            setTimeout(() => {
                if (alert.style.display !== 'none') {
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateY(-20px)';
                    setTimeout(() => {
                        alert.style.display = 'none';
                    }, 300);
                }
            }, 5000);
        });
        
        // Gestion des modales
        const modals = document.querySelectorAll('.modal-loove');
        const modalCloseButtons = document.querySelectorAll('.close-btn-loove');
        
        modalCloseButtons.forEach(button => {
            button.addEventListener('click', function() {
                const modal = this.closest('.modal-loove');
                if (modal) {
                    modal.style.display = 'none';
                }
            });
        });
        
        // Fermer les modales en cliquant à l'extérieur
        modals.forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    modal.style.display = 'none';
                }
            });
        });
        
        // Smooth scroll pour les liens d'ancrage
        const anchorLinks = document.querySelectorAll('a[href^="#"]');
        anchorLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                if (href !== '#' && href !== '') {
                    e.preventDefault();
                    const target = document.querySelector(href);
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                }
            });
        });
        
        // Gestion spéciale du lien Admin
        const adminLink = document.querySelector('.admin-link');
        if (adminLink) {
            adminLink.addEventListener('click', function(e) {
                console.log('Clic sur le lien Admin détecté');
                // Le lien fonctionne normalement
            });
        }
    });
});

// Fonction pour le darkmode toggle (si implémenté)
function toggleDarkMode() {
    const body = document.body;
    body.classList.toggle('light-mode');
    
    // Sauvegarder la préférence
    if (body.classList.contains('light-mode')) {
        localStorage.setItem('theme', 'light');
    } else {
        localStorage.setItem('theme', 'dark');
    }
}

// Vérifier le thème au chargement
(function() {
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'light') {
        document.body.classList.add('light-mode');
    }
})();

// Fonction utilitaire pour afficher des notifications toast
function showToast(message, type = 'info', duration = 3000) {
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 1rem 1.5rem;
        background: var(--color-surface);
        border-left: 4px solid var(--color-${type === 'error' ? 'error' : type === 'success' ? 'success' : 'primary'});
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 10000;
        transform: translateX(100%);
        transition: transform 0.3s ease;
        max-width: 300px;
    `;
    
    toast.innerHTML = `
        <div style="display: flex; align-items: center; gap: 0.5rem;">
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
            <span>${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" style="background:none;border:none;margin-left:auto;cursor:pointer;">&times;</button>
        </div>
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.transform = 'translateX(0)';
    }, 100);
    
    setTimeout(() => {
        toast.style.transform = 'translateX(100%)';
        setTimeout(() => {
            if (toast.parentElement) {
                toast.remove();
            }
        }, 300);
    }, duration);
}

// Fonction utilitaire pour les onglets
function openTab(evt, tabName) {
    const tabcontent = document.getElementsByClassName("tab-content");
    for (let i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    const tablinks = document.getElementsByClassName("tab-link");
    for (let i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }

    const targetTab = document.getElementById(tabName);
    if (targetTab) {
        targetTab.style.display = "block";
    }
    
    if (evt && evt.currentTarget) {
        evt.currentTarget.className += " active";
    }
}
        targetTab.style.display = "block";
    
    
    if (evt && evt.currentTarget) {
        evt.currentTarget.className += " active";
    }

     100;
    
    setTimeout(() => {
        toast.style.transform = 'translateX(100%)';
        setTimeout(() => {
            if (toast.parentElement) {
                toast.remove();
            }
        }, 300);
    }, duration);

