<nav class="navbar-loove">
    <div class="navbar-container">
        <a href="<?php echo BASEURL; ?>" class="navbar-brand-loove">
            <i class="fas fa-heart"></i>
            Loove
        </a>
        
        <button class="navbar-toggler-loove" type="button" id="navToggle">
            <span></span>
            <span></span>
            <span></span>
        </button>
        
        <div class="navbar-menu-container" id="navMenu">
            <ul class="nav-menu-loove">
                <?php if(isset($_SESSION['user_id'])) : ?>
                    <li class="nav-item-loove">
                        <a class="nav-link-loove" href="<?php echo BASEURL; ?>">
                            <i class="fas fa-home"></i>
                            <span>Accueil</span>
                        </a>
                    </li>
                    
                    <li class="nav-item-loove">
                        <a class="nav-link-loove" href="<?php echo BASEURL; ?>/discover">
                            <i class="fas fa-search"></i>
                            <span>Découvrir</span>
                        </a>
                    </li>
                    
                    <li class="nav-item-loove">
                        <a class="nav-link-loove" href="<?php echo BASEURL; ?>/messages">
                            <i class="fas fa-envelope"></i>
                            <span>Messages</span>
                        </a>
                    </li>
                    
                    <li class="nav-item-loove">
                        <a class="nav-link-loove" href="<?php echo BASEURL; ?>/matches">
                            <i class="fas fa-heart"></i>
                            <span>Matchs</span>
                        </a>
                    </li>
                    
                    <li class="nav-item-loove profile-dropdown">
                        <a class="nav-link-loove profile-toggle" href="#" id="profileToggle">
                            <div class="profile-avatar-mini">
                                <?php echo isset($_SESSION['user_name']) ? strtoupper(substr($_SESSION['user_name'], 0, 1)) : 'U'; ?>
                            </div>
                            <span>Profil</span>
                            <i class="fas fa-chevron-down"></i>
                        </a>
                        <div class="dropdown-menu" id="profileDropdown">
                            <a href="<?php echo BASEURL; ?>/profiles/edit/<?php echo $_SESSION['user_id']; ?>" class="dropdown-item">
                                <i class="fas fa-user-edit"></i> Mon profil
                            </a>
                            <a href="<?php echo BASEURL; ?>/subscriptions" class="dropdown-item">
                                <i class="fas fa-crown"></i> Abonnement
                            </a>
                            <a href="<?php echo BASEURL; ?>/settings" class="dropdown-item">
                                <i class="fas fa-cog"></i> Paramètres
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="<?php echo BASEURL; ?>/users/logout" class="dropdown-item">
                                <i class="fas fa-sign-out-alt"></i> Déconnexion
                            </a>
                        </div>
                    </li>
                <?php else : ?>
                    <li class="nav-item-loove">
                        <a class="nav-link-loove" href="<?php echo BASEURL; ?>">
                            <i class="fas fa-home"></i>
                            <span>Accueil</span>
                        </a>
                    </li>
                    
                    <li class="nav-item-loove">
                        <a class="nav-link-loove" href="<?php echo BASEURL; ?>/pages/about">
                            <i class="fas fa-info-circle"></i>
                            <span>À propos</span>
                        </a>
                    </li>
                    
                    <li class="nav-item-loove">
                        <a class="nav-link-loove" href="<?php echo BASEURL; ?>/users/login">
                            <i class="fas fa-sign-in-alt"></i>
                            <span>Connexion</span>
                        </a>
                    </li>
                    <li class="nav-item-loove">
                        <a class="btn-loove btn-loove-primary" href="<?php echo BASEURL; ?>/users/register">
                            <i class="fas fa-user-plus"></i>
                            <span>Inscription</span>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<style>
.admin-link {
    background: linear-gradient(135deg, #f59e0b, #d97706) !important;
    color: white !important;
    border-radius: 8px !important;
    font-weight: 600 !important;
}

.admin-link:hover {
    background: linear-gradient(135deg, #d97706, #b45309) !important;
    transform: translateY(-2px);
}

.navbar-toggler-loove {
    display: none;
    flex-direction: column;
    background: transparent;
    border: none;
    cursor: pointer;
    padding: 0.5rem;
}

.navbar-toggler-loove span {
    width: 25px;
    height: 3px;
    background: var(--color-text-primary);
    margin: 2px 0;
    transition: 0.3s;
    border-radius: 2px;
}

@media (max-width: 992px) {
    .navbar-toggler-loove {
        display: flex;
    }
    
    .navbar-menu-container {
        position: fixed;
        top: 0;
        right: -100%;
        height: 100vh;
        width: 300px;
        background: var(--color-surface);
        transition: right 0.3s ease;
        padding-top: 80px;
        box-shadow: -5px 0 15px rgba(0,0,0,0.1);
        z-index: 999;
    }
    
    .navbar-menu-container.active {
        right: 0;
    }
    
    .nav-menu-loove {
        flex-direction: column;
        padding: 1rem;
    }
    
    .nav-item-loove {
        width: 100%;
        margin-bottom: 0.5rem;
    }
    
    .nav-link-loove {
        width: 100%;
        justify-content: flex-start;
        padding: 1rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
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
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.profile-dropdown')) {
                profileDropdown.classList.remove('show');
            }
        });
    }
    
    // Close mobile menu when clicking on links
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
});
</script>