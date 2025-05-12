<section class="hero">
    <!-- Success notification -->
    <?php if(isset($_SESSION['success_message'])): ?>
    <div class="notification success-notification">
        <div class="notification-content">
            <i class="fas fa-check-circle notification-icon"></i>
            <p><?= $_SESSION['success_message'] ?></p>
            <button class="notification-close" onclick="this.parentElement.parentElement.style.display='none';">&times;</button>
        </div>
    </div>
    <?php 
        // Remove the message after displaying it once
        unset($_SESSION['success_message']);
    endif; 
    ?>
    
    <div class="hero-content">
        <h1>Find Your Perfect Match</h1>
        <p>Join thousands of singles looking for meaningful connections</p>
        <?php if (!isset($_SESSION['user_id'])): ?>
            <div class="hero-buttons">
                <a href="<?= APP_URL ?>/login" class="btn btn-primary">Login</a>
                <a href="<?= APP_URL ?>/register" class="btn btn-secondary">Sign Up</a>
            </div>
        <?php else: ?>
            <div class="hero-buttons">
                <a href="<?= APP_URL ?>/discover" class="btn btn-primary">Start Matching</a>
            </div>
        <?php endif; ?>
    </div>
</section>

<section class="features">
    <div class="container">
        <h2>Why Choose Loove?</h2>
        
        <div class="feature-cards">
            <div class="feature-card">
                <i class="feature-icon fa fa-heart"></i>
                <h3>Smart Matching</h3>
                <p>Our algorithm helps you find compatible matches based on your preferences and interests.</p>
            </div>
            
            <div class="feature-card">
                <i class="feature-icon fa fa-comments"></i>
                <h3>Real Conversations</h3>
                <p>Connect through our secure messaging system and get to know your matches.</p>
            </div>
            
            <div class="feature-card">
                <i class="feature-icon fa fa-map-marker"></i>
                <h3>Local Dating</h3>
                <p>Meet singles in your area and turn online connections into real-life relationships.</p>
            </div>
        </div>
    </div>
</section>

<section class="how-it-works">
    <div class="container">
        <h2>How Loove Works</h2>
        
        <div class="steps">
            <div class="step">
                <div class="step-number">1</div>
                <h3>Create Your Profile</h3>
                <p>Sign up and tell us about yourself, your interests, and what you're looking for.</p>
            </div>
            
            <div class="step">
                <div class="step-number">2</div>
                <h3>Discover Matches</h3>
                <p>Browse profiles of singles who match your preferences and send likes.</p>
            </div>
            
            <div class="step">
                <div class="step-number">3</div>
                <h3>Start Chatting</h3>
                <p>When you match with someone, start a conversation and see where it leads!</p>
            </div>
        </div>
    </div>
</section>
