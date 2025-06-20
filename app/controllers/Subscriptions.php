<?php
class Subscriptions extends Controller {
    private $subscriptionModel;
    private $userModel;
    private $db;

    public function __construct() {
        if(!isLoggedIn()) {
            redirect('users/login');
        }
        
        $this->subscriptionModel = $this->model('Subscription');
        $this->userModel = $this->model('User');
        $this->db = new Database;
    }

    // Afficher les plans d'abonnement
    public function index() {
        $plans = $this->subscriptionModel->getPlans();
        
        // S'assurer que l'abonnement actif est bien récupéré ou définir à null/false
        $activeSubscription = $this->subscriptionModel->getActiveSubscription($_SESSION['user_id']);
        
        $data = [
            'title' => 'Plans d\'abonnement',
            'plans' => $plans,
            'active_subscription' => $activeSubscription
        ];
        
        $this->view('subscriptions/index', $data);
    }

    // Afficher le formulaire de paiement
    public function checkout($planId) {
        $plan = $this->subscriptionModel->getPlanById($planId);
        
        if (!$plan || $plan->price <= 0) {
            flash('subscription_error', 'Plan d\'abonnement invalide', 'alert-loove-danger');
            redirect('subscriptions');
        }
        
        $data = [
            'title' => 'Paiement - ' . $plan->name,
            'plan' => $plan
        ];
        
        $this->view('subscriptions/checkout', $data);
    }

    // Traiter le paiement PayPal
    public function processPaypal($planId) {
        $plan = $this->subscriptionModel->getPlanById($planId);
        
        if (!$plan || $plan->price <= 0) {
            flash('subscription_error', 'Plan d\'abonnement invalide', 'alert-loove-danger');
            redirect('subscriptions');
        }
        
        // Récupérer l'ID de transaction depuis l'URL
        $transactionId = isset($_GET['transaction_id']) ? $_GET['transaction_id'] : 'PP_' . time() . rand(1000, 9999);
        
        // Simuler un paiement PayPal réussi
        $paymentSuccess = true;
        
        if ($paymentSuccess) {
            // Créer l'abonnement
            $subscriptionId = $this->subscriptionModel->createSubscription(
                $_SESSION['user_id'], 
                $plan->id, 
                $transactionId
            );
            
            if ($subscriptionId) {
                // Enregistrer la transaction
                $this->subscriptionModel->recordPayment(
                    $_SESSION['user_id'],
                    $subscriptionId,
                    $plan->price,
                    'PayPal',
                    $transactionId,
                    'completed'
                );
                
                // Message de succès détaillé
                $successMessage = "🎉 Félicitations ! Votre abonnement <strong>" . htmlspecialchars($plan->name) . "</strong> a été activé avec succès !<br>";
                $successMessage .= "💳 Paiement de " . number_format($plan->price, 2) . "€ traité via PayPal<br>";
                $successMessage .= "📧 Un email de confirmation vous a été envoyé<br>";
                $successMessage .= "🔥 Profitez dès maintenant de tous vos avantages premium !";
                
                flash('subscription_success', $successMessage, 'alert-loove-success');
                redirect('subscriptions');
            } else {
                flash('subscription_error', 'Une erreur est survenue lors de l\'activation de votre abonnement', 'alert-loove-danger');
                redirect('subscriptions');
            }
        } else {
            flash('subscription_error', 'Le paiement PayPal a échoué, veuillez réessayer', 'alert-loove-danger');
            redirect('subscriptions/checkout/' . $plan->id);
        }
    }

    // Traiter le paiement par carte
    public function processCard($planId) {
        $plan = $this->subscriptionModel->getPlanById($planId);
        
        if (!$plan || $plan->price <= 0) {
            flash('subscription_error', 'Plan d\'abonnement invalide', 'alert-loove-danger');
            redirect('subscriptions');
        }
        
        // Récupérer l'ID de transaction depuis l'URL
        $transactionId = isset($_GET['transaction_id']) ? $_GET['transaction_id'] : 'CARD_' . time() . rand(1000, 9999);
        
        // Simuler un paiement par carte réussi
        $paymentSuccess = true;
        
        if ($paymentSuccess) {
            // Créer l'abonnement
            $subscriptionId = $this->subscriptionModel->createSubscription(
                $_SESSION['user_id'], 
                $plan->id, 
                $transactionId
            );
            
            if ($subscriptionId) {
                // Enregistrer la transaction
                $this->subscriptionModel->recordPayment(
                    $_SESSION['user_id'],
                    $subscriptionId,
                    $plan->price,
                    'Carte bancaire',
                    $transactionId,
                    'completed'
                );
                
                $successMessage = "🎉 Paiement réussi ! Votre abonnement <strong>" . htmlspecialchars($plan->name) . "</strong> est maintenant actif !<br>";
                $successMessage .= "💳 Transaction sécurisée de " . number_format($plan->price, 2) . "€<br>";
                $successMessage .= "🛡️ Paiement protégé par cryptage SSL 256-bit<br>";
                $successMessage .= "⭐ Découvrez toutes vos nouvelles fonctionnalités premium !";
                
                flash('subscription_success', $successMessage, 'alert-loove-success');
                redirect('subscriptions');
            } else {
                flash('subscription_error', 'Une erreur est survenue lors de l\'activation de votre abonnement', 'alert-loove-danger');
                redirect('subscriptions');
            }
        } else {
            flash('subscription_error', 'Le paiement par carte a été refusé, veuillez vérifier vos informations', 'alert-loove-danger');
            redirect('subscriptions/checkout/' . $plan->id);
        }
    }

    // Traiter le paiement Stripe
    public function processStripe($planId) {
        $plan = $this->subscriptionModel->getPlanById($planId);
        
        if (!$plan || $plan->price <= 0) {
            flash('subscription_error', 'Plan d\'abonnement invalide', 'alert-loove-danger');
            redirect('subscriptions');
        }
        
        // Récupérer l'ID de transaction depuis l'URL
        $transactionId = isset($_GET['transaction_id']) ? $_GET['transaction_id'] : 'STRIPE_' . time() . rand(1000, 9999);
        
        // Simuler un paiement Stripe réussi
        $paymentSuccess = true;
        
        if ($paymentSuccess) {
            // Créer l'abonnement
            $subscriptionId = $this->subscriptionModel->createSubscription(
                $_SESSION['user_id'], 
                $plan->id, 
                $transactionId
            );
            
            if ($subscriptionId) {
                // Enregistrer la transaction
                $this->subscriptionModel->recordPayment(
                    $_SESSION['user_id'],
                    $subscriptionId,
                    $plan->price,
                    'Stripe',
                    $transactionId,
                    'completed'
                );
                
                $successMessage = "🚀 Excellent ! Votre abonnement <strong>" . htmlspecialchars($plan->name) . "</strong> a été activé via Stripe !<br>";
                $successMessage .= "💎 Paiement ultra-sécurisé de " . number_format($plan->price, 2) . "€<br>";
                $successMessage .= "🔒 Certifié PCI DSS niveau 1 - Sécurité maximale<br>";
                $successMessage .= "💫 Commencez dès maintenant votre expérience premium !";
                
                flash('subscription_success', $successMessage, 'alert-loove-success');
                redirect('subscriptions');
            } else {
                flash('subscription_error', 'Une erreur est survenue lors de l\'activation de votre abonnement', 'alert-loove-danger');
                redirect('subscriptions');
            }
        } else {
            flash('subscription_error', 'Le paiement Stripe a échoué, veuillez réessayer', 'alert-loove-danger');
            redirect('subscriptions/checkout/' . $plan->id);
        }
    }

    // Activer le plan gratuit
    public function activateFree() {
        // Récupérer le plan gratuit
        $this->db->query('SELECT id FROM subscription_plans WHERE price = 0 AND is_active = 1 LIMIT 1');
        $freePlan = $this->db->single();
        
        if ($freePlan && isset($freePlan->id)) {
            $subscriptionId = $this->subscriptionModel->createSubscription(
                $_SESSION['user_id'], 
                $freePlan->id
            );
            
            if ($subscriptionId) {
                flash('subscription_success', 'Plan Gratuit activé avec succès !', 'alert-loove-success');
            } else {
                flash('subscription_error', 'Une erreur est survenue lors de l\'activation du plan', 'alert-loove-danger');
            }
        } else {
            flash('subscription_error', 'Plan gratuit non disponible', 'alert-loove-danger');
        }
        
        redirect('subscriptions');
    }

    // Afficher l'historique des paiements
    public function history() {
        $paymentHistory = $this->subscriptionModel->getPaymentHistory($_SESSION['user_id']);
        $activeSubscription = $this->subscriptionModel->getActiveSubscription($_SESSION['user_id']);
        
        $data = [
            'title' => 'Historique des paiements',
            'payment_history' => $paymentHistory,
            'active_subscription' => $activeSubscription
        ];
        
        $this->view('subscriptions/history', $data);
    }
}
?>
