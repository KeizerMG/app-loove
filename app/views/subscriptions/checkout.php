<?php require APPROOT . '/views/includes/header.php'; ?>

<style>
.checkout-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 2rem 0;
}

.checkout-card {
    background: var(--color-surface);
    border-radius: var(--border-radius-lg);
    box-shadow: var(--shadow-lg);
    overflow: hidden;
    margin-bottom: 2rem;
}

.checkout-header {
    background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
    color: white;
    padding: 2rem;
    text-align: center;
}

.plan-summary {
    background: var(--color-surface-variant);
    padding: 2rem;
    margin-bottom: 2rem;
}

.payment-methods {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin: 2rem 0;
}

.payment-method {
    border: 2px solid var(--color-divider);
    border-radius: var(--border-radius-md);
    padding: 1.5rem;
    text-align: center;
    cursor: pointer;
    transition: var(--transition-normal);
    background: var(--color-surface);
}

.payment-method:hover {
    border-color: var(--color-primary);
    box-shadow: var(--shadow-md);
}

.payment-method.selected {
    border-color: var(--color-primary);
    background: var(--color-primary-soft);
}

.payment-method i {
    font-size: 2rem;
    margin-bottom: 1rem;
    color: var(--color-primary);
}

.payment-form {
    padding: 2rem;
    display: none;
}

.payment-form.active {
    display: block;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.security-info {
    background: rgba(16, 185, 129, 0.1);
    border: 1px solid var(--color-secondary);
    border-radius: var(--border-radius-md);
    padding: 1rem;
    margin: 1.5rem 0;
}

.price-breakdown {
    background: var(--color-surface-variant);
    border-radius: var(--border-radius-md);
    padding: 1.5rem;
    margin: 2rem 0;
}

.price-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 0.5rem;
}

.price-total {
    border-top: 2px solid var(--color-divider);
    padding-top: 1rem;
    margin-top: 1rem;
    font-weight: 700;
    font-size: 1.2rem;
}

.loading-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.7);
    z-index: 9999;
    align-items: center;
    justify-content: center;
}

.loading-content {
    background: white;
    border-radius: var(--border-radius-lg);
    padding: 3rem;
    text-align: center;
    max-width: 400px;
    margin: 0 auto;
}

.spinner {
    width: 50px;
    height: 50px;
    border: 4px solid var(--color-primary-soft);
    border-top: 4px solid var(--color-primary);
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin: 0 auto 1rem;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.success-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.7);
    z-index: 10000;
    align-items: center;
    justify-content: center;
}

.success-content {
    background: white;
    border-radius: var(--border-radius-lg);
    padding: 3rem;
    text-align: center;
    max-width: 500px;
    margin: 0 auto;
    animation: slideUp 0.5s ease;
}

.success-icon {
    width: 80px;
    height: 80px;
    background: var(--color-success);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 2rem;
    color: white;
    font-size: 2rem;
}

@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .payment-methods {
        grid-template-columns: 1fr;
    }
}
</style>

<section class="checkout-container">
    <div class="checkout-card">
        <div class="checkout-header">
            <h1><i class="fas fa-crown"></i> Finaliser votre abonnement</h1>
            <p>Sécurisé et crypté avec SSL</p>
        </div>

        <!-- Résumé du plan -->
        <div class="plan-summary">
            <h3>Récapitulatif de votre commande</h3>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 1rem;">
                <div>
                    <h4 style="color: var(--color-primary);"><?php echo htmlspecialchars($data['plan']->name); ?></h4>
                    <p><?php echo htmlspecialchars($data['plan']->description); ?></p>
                    <small>Durée: <?php echo $data['plan']->duration_days; ?> jours</small>
                </div>
                <div style="text-align: right;">
                    <div style="font-size: 2rem; font-weight: 700; color: var(--color-primary);">
                        <?php echo number_format($data['plan']->price, 2); ?>€
                    </div>
                    <small>TTC</small>
                </div>
            </div>
        </div>

        <!-- Méthodes de paiement -->
        <div style="padding: 2rem;">
            <h3>Choisissez votre méthode de paiement</h3>
            <div class="payment-methods">
                <div class="payment-method" data-method="paypal">
                    <i class="fab fa-paypal" style="color: #0070ba;"></i>
                    <h4>PayPal</h4>
                    <p>Paiement sécurisé via PayPal</p>
                </div>
                
                <div class="payment-method" data-method="card">
                    <i class="fas fa-credit-card"></i>
                    <h4>Carte bancaire</h4>
                    <p>Visa, Mastercard, American Express</p>
                </div>
                
                <div class="payment-method" data-method="stripe">
                    <i class="fab fa-stripe" style="color: #635bff;"></i>
                    <h4>Stripe</h4>
                    <p>Paiement ultra-sécurisé</p>
                </div>
            </div>

            <!-- Formulaire PayPal -->
            <div class="payment-form" id="paypal-form">
                <h4><i class="fab fa-paypal"></i> Paiement PayPal</h4>
                <p>Vous allez être redirigé vers PayPal pour finaliser votre paiement de manière sécurisée.</p>
                
                <div class="security-info">
                    <i class="fas fa-shield-alt"></i>
                    <strong>Paiement 100% sécurisé</strong><br>
                    Vos données bancaires ne transitent jamais par nos serveurs.
                </div>
                
                <button type="button" class="btn-loove btn-loove-primary" style="width: 100%;" onclick="processPayment('paypal')">
                    <i class="fab fa-paypal"></i> Payer avec PayPal - <?php echo number_format($data['plan']->price, 2); ?>€
                </button>
            </div>

            <!-- Formulaire Carte bancaire -->
            <div class="payment-form" id="card-form">
                <h4><i class="fas fa-credit-card"></i> Paiement par carte</h4>
                
                <form id="cardPaymentForm">
                    <div class="form-group-loove">
                        <label class="form-label-loove">Numéro de carte</label>
                        <input type="text" class="form-control-loove" placeholder="1234 5678 9012 3456" maxlength="19" id="cardNumber">
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group-loove">
                            <label class="form-label-loove">Date d'expiration</label>
                            <input type="text" class="form-control-loove" placeholder="MM/AA" maxlength="5" id="cardExpiry">
                        </div>
                        <div class="form-group-loove">
                            <label class="form-label-loove">Code de sécurité</label>
                            <input type="text" class="form-control-loove" placeholder="123" maxlength="4" id="cardCvv">
                        </div>
                    </div>
                    
                    <div class="form-group-loove">
                        <label class="form-label-loove">Nom sur la carte</label>
                        <input type="text" class="form-control-loove" placeholder="Jean Dupont" id="cardName">
                    </div>
                    
                    <div class="security-info">
                        <i class="fas fa-lock"></i>
                        <strong>Sécurité SSL 256-bit</strong><br>
                        Vos informations de paiement sont cryptées et sécurisées.
                    </div>
                    
                    <button type="button" class="btn-loove btn-loove-primary" style="width: 100%;" onclick="processPayment('card')">
                        <i class="fas fa-lock"></i> Payer <?php echo number_format($data['plan']->price, 2); ?>€
                    </button>
                </form>
            </div>

            <!-- Formulaire Stripe -->
            <div class="payment-form" id="stripe-form">
                <h4><i class="fab fa-stripe"></i> Paiement Stripe</h4>
                <p>Stripe est un leader mondial du paiement en ligne, utilisé par des millions d'entreprises.</p>
                
                <div class="security-info">
                    <i class="fas fa-certificate"></i>
                    <strong>Certifié PCI DSS niveau 1</strong><br>
                    Le plus haut niveau de sécurité pour les paiements en ligne.
                </div>
                
                <button type="button" class="btn-loove btn-loove-primary" style="width: 100%;" onclick="processPayment('stripe')">
                    <i class="fab fa-stripe"></i> Payer avec Stripe - <?php echo number_format($data['plan']->price, 2); ?>€
                </button>
            </div>

            <!-- Récapitulatif des prix -->
            <div class="price-breakdown">
                <h4>Détail de votre commande</h4>
                <div class="price-row">
                    <span><?php echo htmlspecialchars($data['plan']->name); ?></span>
                    <span><?php echo number_format($data['plan']->price, 2); ?>€</span>
                </div>
                <div class="price-row">
                    <span>TVA (0%)</span>
                    <span>0,00€</span>
                </div>
                <div class="price-row price-total">
                    <span><strong>Total à payer</strong></span>
                    <span><strong><?php echo number_format($data['plan']->price, 2); ?>€</strong></span>
                </div>
            </div>

            <div style="text-align: center; margin-top: 2rem;">
                <p style="color: var(--color-text-secondary); font-size: 0.9rem;">
                    <i class="fas fa-info-circle"></i>
                    En procédant au paiement, vous acceptez nos 
                    <a href="<?php echo BASEURL; ?>/pages/terms">conditions d'utilisation</a>.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Overlay de chargement -->
<div class="loading-overlay" id="loadingOverlay">
    <div class="loading-content">
        <div class="spinner"></div>
        <h3>Traitement de votre paiement...</h3>
        <p>Veuillez patienter, cela peut prendre quelques secondes.</p>
    </div>
</div>

<!-- Modal de succès -->
<div class="success-modal" id="successModal">
    <div class="success-content">
        <div class="success-icon">
            <i class="fas fa-check"></i>
        </div>
        <h2 style="color: var(--color-success); margin-bottom: 1rem;">Paiement réussi !</h2>
        <p style="margin-bottom: 2rem;">
            Félicitations ! Votre abonnement <strong><?php echo htmlspecialchars($data['plan']->name); ?></strong> 
            a été activé avec succès.
        </p>
        <div style="background: var(--color-surface-variant); padding: 1.5rem; border-radius: var(--border-radius-md); margin-bottom: 2rem;">
            <h4>Votre reçu</h4>
            <div style="display: flex; justify-content: space-between; margin: 0.5rem 0;">
                <span>Plan :</span>
                <span><?php echo htmlspecialchars($data['plan']->name); ?></span>
            </div>
            <div style="display: flex; justify-content: space-between; margin: 0.5rem 0;">
                <span>Montant :</span>
                <span><?php echo number_format($data['plan']->price, 2); ?>€</span>
            </div>
            <div style="display: flex; justify-content: space-between; margin: 0.5rem 0;">
                <span>Transaction ID :</span>
                <span id="transactionId"></span>
            </div>
        </div>
        <div style="display: flex; gap: 1rem; justify-content: center;">
            <a href="<?php echo BASEURL; ?>/subscriptions" class="btn-loove btn-loove-primary">
                <i class="fas fa-crown"></i> Voir mes abonnements
            </a>
            <a href="<?php echo BASEURL; ?>/discover" class="btn-loove btn-loove-secondary">
                <i class="fas fa-heart"></i> Commencer à utiliser
            </a>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestion de la sélection des méthodes de paiement
    const paymentMethods = document.querySelectorAll('.payment-method');
    const paymentForms = document.querySelectorAll('.payment-form');
    
    paymentMethods.forEach(method => {
        method.addEventListener('click', function() {
            // Désélectionner tous
            paymentMethods.forEach(m => m.classList.remove('selected'));
            paymentForms.forEach(f => f.classList.remove('active'));
            
            // Sélectionner celui-ci
            this.classList.add('selected');
            const formId = this.dataset.method + '-form';
            document.getElementById(formId).classList.add('active');
        });
    });
    
    // Sélectionner PayPal par défaut
    document.querySelector('[data-method="paypal"]').click();
    
    // Formatage automatique du numéro de carte
    const cardNumber = document.getElementById('cardNumber');
    if (cardNumber) {
        cardNumber.addEventListener('input', function() {
            let value = this.value.replace(/\s/g, '');
            let formattedValue = value.replace(/(.{4})/g, '$1 ').trim();
            if (formattedValue.length > 19) {
                formattedValue = formattedValue.substring(0, 19);
            }
            this.value = formattedValue;
        });
    }
    
    // Formatage de la date d'expiration
    const cardExpiry = document.getElementById('cardExpiry');
    if (cardExpiry) {
        cardExpiry.addEventListener('input', function() {
            let value = this.value.replace(/\D/g, '');
            if (value.length >= 2) {
                value = value.substring(0, 2) + '/' + value.substring(2, 4);
            }
            this.value = value;
        });
    }
    
    // CVV - seulement des chiffres
    const cardCvv = document.getElementById('cardCvv');
    if (cardCvv) {
        cardCvv.addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, '');
        });
    }
});

function processPayment(method) {
    const loadingOverlay = document.getElementById('loadingOverlay');
    const successModal = document.getElementById('successModal');
    
    // Validation spécifique pour les cartes
    if (method === 'card') {
        const cardNumber = document.getElementById('cardNumber').value.replace(/\s/g, '');
        const cardExpiry = document.getElementById('cardExpiry').value;
        const cardCvv = document.getElementById('cardCvv').value;
        const cardName = document.getElementById('cardName').value;
        
        if (!cardNumber || cardNumber.length < 13) {
            alert('Veuillez entrer un numéro de carte valide');
            return;
        }
        
        if (!cardExpiry || cardExpiry.length !== 5) {
            alert('Veuillez entrer une date d\'expiration valide (MM/AA)');
            return;
        }
        
        if (!cardCvv || cardCvv.length < 3) {
            alert('Veuillez entrer un code de sécurité valide');
            return;
        }
        
        if (!cardName.trim()) {
            alert('Veuillez entrer le nom sur la carte');
            return;
        }
    }
    
    // Afficher le loading
    loadingOverlay.style.display = 'flex';
    
    // Simuler le traitement du paiement
    setTimeout(() => {
        // Cacher le loading
        loadingOverlay.style.display = 'none';
        
        // Générer un ID de transaction
        const transactionId = method.toUpperCase() + '_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
        document.getElementById('transactionId').textContent = transactionId;
        
        // Afficher le succès
        successModal.style.display = 'flex';
        
        // Rediriger vers la méthode de traitement du paiement
        setTimeout(() => {
            window.location.href = '<?php echo BASEURL; ?>/subscriptions/process' + method.charAt(0).toUpperCase() + method.slice(1) + '/<?php echo $data['plan']->id; ?>?transaction_id=' + transactionId;
        }, 3000);
        
    }, 2000 + Math.random() * 2000); // Entre 2 et 4 secondes
}

// Empêcher la fermeture accidentelle pendant le paiement
let paymentInProgress = false;

window.addEventListener('beforeunload', function(e) {
    const loadingOverlay = document.getElementById('loadingOverlay');
    if (loadingOverlay.style.display === 'flex') {
        e.preventDefault();
        e.returnValue = 'Un paiement est en cours. Êtes-vous sûr de vouloir quitter cette page ?';
        return e.returnValue;
    }
});
</script>

<?php require APPROOT . '/views/includes/footer.php'; ?>
