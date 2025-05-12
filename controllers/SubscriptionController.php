<?php
require_once 'controllers/Controller.php';
require_once 'models/Subscription.php';
require_once 'models/User.php';
require_once 'utils/Auth.php';

class SubscriptionController extends Controller {
    private $subscriptionModel;
    private $userModel;
    
    public function __construct() {
        $this->subscriptionModel = new Subscription();
        $this->userModel = new User();
        
        // These pages require login
        if (in_array($_SERVER['REQUEST_METHOD'] . ' ' . $_SERVER['REQUEST_URI'], [
            'GET /premium',
            'POST /premium/subscribe',
            'POST /premium/cancel',
            'GET /premium/history'
        ])) {
            Auth::requireLogin();
        }
    }
    
    public function showPlans() {
        // Get current user's subscription
        $subscription = null;
        if (Auth::isLoggedIn()) {
            $userId = Auth::getCurrentUserId();
            $subscription = $this->subscriptionModel->getByUserId($userId);
        }
        
        $this->render('premium/index', [
            'title' => 'Premium Subscription Plans',
            'subscription' => $subscription,
            'plans' => PLANS
        ]);
    }
    
    public function subscribe() {
        if (!$this->validateCSRF()) {
            $this->redirect('/premium?error=invalid_csrf');
            return;
        }
        
        $userId = Auth::getCurrentUserId();
        $planType = $_POST['plan_type'] ?? '';
        $paymentMethod = $_POST['payment_method'] ?? '';
        
        if (!in_array($planType, ['premium', 'gold'])) {
            $this->redirect('/premium?error=invalid_plan');
            return;
        }
        
        // Process payment (this would connect to a payment gateway in production)
        // For demo purposes, we'll just create the subscription
        $success = $this->subscriptionModel->create([
            'user_id' => $userId,
            'plan_type' => $planType,
            'start_date' => date('Y-m-d H:i:s'),
            'end_date' => date('Y-m-d H:i:s', strtotime('+1 month')),
            'payment_status' => 'completed'
        ]);
        
        if ($success) {
            // Log the successful subscription
            Logger::activity($userId, "Subscribed to $planType plan");
            
            $this->redirect('/premium?success=subscribed');
        } else {
            $this->redirect('/premium?error=subscription_failed');
        }
    }
    
    public function cancelSubscription() {
        if (!$this->validateCSRF()) {
            $this->redirect('/premium?error=invalid_csrf');
            return;
        }
        
        $userId = Auth::getCurrentUserId();
        $subscription = $this->subscriptionModel->getByUserId($userId);
        
        if (!$subscription) {
            $this->redirect('/premium?error=no_subscription');
            return;
        }
        
        $success = $this->subscriptionModel->update($subscription['id'], [
            'payment_status' => 'canceled'
        ]);
        
        if ($success) {
            // Log the cancellation
            Logger::activity($userId, "Cancelled {$subscription['plan_type']} subscription");
            
            $this->redirect('/premium?success=cancelled');
        } else {
            $this->redirect('/premium?error=cancellation_failed');
        }
    }
    
    public function showSubscriptionHistory() {
        $userId = Auth::getCurrentUserId();
        $history = $this->subscriptionModel->getSubscriptionHistory($userId);
        
        $this->render('premium/history', [
            'title' => 'Subscription History',
            'history' => $history
        ]);
    }
}
?>
