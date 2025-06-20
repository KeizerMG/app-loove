<?php require APPROOT . '/views/includes/header.php'; ?>

<style>
    body, html {
        background-color: #0f172a !important; 
        margin: 0;
        padding: 0;
        height: 100%;
        overflow-x: hidden;
    }
    
    .navbar-loove {
        background-color: #0f172a !important;
        box-shadow: 0 10px 30px -10px rgba(2, 12, 27, 0.7) !important;
    }
    
    .error-404-container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: calc(100vh - 150px);
        padding: 2rem;
        z-index: 1;
        position: relative;
    }
    
    
    .error-404-container::before,
    .error-404-container::after {
        content: '';
        position: absolute;
        width: 300px;
        height: 300px;
        border-radius: 50%;
        filter: blur(100px);
        opacity: 0.15;
        z-index: -1;
        animation: float 15s infinite alternate ease-in-out;
    }
    
    .error-404-container::before {
        background: linear-gradient(to right, #4facfe, #00f2fe);
        top: 10%;
        left: 15%;
        animation-delay: 0s;
    }
    
    .error-404-container::after {
        background: linear-gradient(to right, #6a11cb, #2575fc);
        bottom: 10%;
        right: 15%;
        animation-delay: -7.5s;
    }
    
    .error-404-content {
        text-align: center;
        background: rgba(15, 23, 42, 0.8);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 3.5rem;
        max-width: 600px;
        width: 100%;
        box-shadow: 
            20px 20px 60px rgba(0, 0, 0, 0.3),
            inset 2px 2px 5px rgba(255, 255, 255, 0.05),
            inset -2px -2px 5px rgba(0, 0, 0, 0.3);
        border: 1px solid rgba(255, 255, 255, 0.05);
        position: relative;
        z-index: 1;
        overflow: hidden;
    }
    
    
    .error-404-content::before {
        content: '';
        position: absolute;
        top: -2px;
        left: -2px;
        width: calc(100% + 4px);
        height: calc(100% + 4px);
        background: linear-gradient(45deg, 
            #4facfe, #00f2fe, #6a11cb, #2575fc, 
            #4facfe, #00f2fe, #6a11cb, #2575fc);
        background-size: 300% 300%;
        z-index: -1;
        border-radius: 20px;
        animation: border-glow 8s linear infinite;
    }
    
    .error-icon {
        position: relative;
        margin-bottom: 2rem;
    }
    
    .error-icon i {
        font-size: 5rem;
        background: linear-gradient(to right, #4facfe, #00f2fe);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        filter: drop-shadow(0 0 10px rgba(79, 172, 254, 0.5));
    }
    
    
    .error-icon::before, 
    .error-icon::after {
        content: '';
        position: absolute;
        border-radius: 50%;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: -1;
    }
    
    .error-icon::before {
        width: 120px;
        height: 120px;
        background: radial-gradient(circle, rgba(79, 172, 254, 0.1) 0%, rgba(79, 172, 254, 0) 70%);
        animation: pulse 2s infinite;
    }
    
    .error-icon::after {
        width: 160px;
        height: 160px;
        background: radial-gradient(circle, rgba(79, 172, 254, 0.05) 0%, rgba(79, 172, 254, 0) 70%);
        animation: pulse 2s infinite 0.5s;
    }
    
    .error-code {
        font-size: 9rem;
        font-weight: 800;
        line-height: 1;
        margin: 0 0 1rem 0;
        background: linear-gradient(to right, #4facfe, #00f2fe, #6a11cb, #2575fc);
        background-size: 300% 300%;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        animation: gradient-shift 8s ease infinite;
        letter-spacing: -2px;
    }
    
    .error-title {
        font-size: 2.2rem;
        color: #fff;
        margin-bottom: 1.5rem;
        font-weight: 700;
        letter-spacing: -0.5px;
    }
    
    .error-message {
        font-size: 1.2rem;
        color: rgba(255, 255, 255, 0.7);
        margin-bottom: 2.5rem;
        line-height: 1.6;
    }
    
    .error-actions {
        display: flex;
        justify-content: center;
        gap: 1.2rem;
        flex-wrap: wrap;
    }
    
    
    .error-actions .btn-loove-primary {
        background: linear-gradient(to right, #4facfe, #00f2fe);
        color: #0f172a;
        border: none;
        padding: 0.8rem 1.8rem;
        font-weight: 600;
        border-radius: 30px;
        box-shadow: 0 10px 20px rgba(79, 172, 254, 0.3);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        z-index: 1;
    }
    
    .error-actions .btn-loove-primary::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(to right, #00f2fe, #4facfe);
        z-index: -1;
        transition: opacity 0.3s ease;
        opacity: 0;
    }
    
    .error-actions .btn-loove-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 25px rgba(79, 172, 254, 0.4);
    }
    
    .error-actions .btn-loove-primary:hover::before {
        opacity: 1;
    }
    
    .error-actions .btn-loove-outline {
        background: transparent;
        color: #4facfe;
        border: 2px solid rgba(79, 172, 254, 0.5);
        padding: 0.8rem 1.8rem;
        font-weight: 600;
        border-radius: 30px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .error-actions .btn-loove-outline:hover {
        border-color: #4facfe;
        background: rgba(79, 172, 254, 0.1);
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
    }
    
    
    @keyframes pulse {
        0% {
            transform: translate(-50%, -50%) scale(0.8);
            opacity: 0.5;
        }
        100% {
            transform: translate(-50%, -50%) scale(1.2);
            opacity: 0;
        }
    }
    
    @keyframes gradient-shift {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }
    
    @keyframes border-glow {
        0% { background-position: 0% 0%; }
        100% { background-position: 300% 300%; }
    }
    
    @keyframes float {
        0% { transform: translateY(0) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(5deg); }
        100% { transform: translateY(20px) rotate(-5deg); }
    }
    
    
    @media (max-width: 768px) {
        .error-404-content {
            padding: 2.5rem 2rem;
        }
        
        .error-code {
            font-size: 7rem;
        }
        
        .error-title {
            font-size: 1.8rem;
        }
    }
    
    @media (max-width: 576px) {
        .error-404-content {
            padding: 2rem 1.5rem;
        }
        
        .error-code {
            font-size: 5rem;
        }
        
        .error-title {
            font-size: 1.5rem;
        }
        
        .error-icon i {
            font-size: 4rem;
        }
    }
</style>

<div class="error-404-container slide-up">
    <div class="error-404-content">
        <div class="error-icon">
            <i class="fas fa-heart-broken"></i>
        </div>
        <h1 class="error-code">404</h1>
        <h2 class="error-title">Page non trouvée</h2>
        <p class="error-message"><?php echo $data['message']; ?></p>
        <div class="error-actions">
            <a href="<?php echo BASEURL; ?>" class="btn-loove btn-loove-primary">
                <i class="fas fa-home"></i> Retour à l'accueil
            </a>
            <a href="javascript:history.back()" class="btn-loove btn-loove-outline">
                <i class="fas fa-arrow-left"></i> Page précédente
            </a>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const footer = document.querySelector('footer');
        if (footer) {
            footer.style.display = 'none';
        }
    });
</script>

<?php require APPROOT . '/views/includes/footer.php'; ?>
