<?php
class Subscription {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // Récupérer tous les plans d'abonnement actifs
    public function getPlans() {
        $this->db->query('SELECT * FROM subscription_plans WHERE is_active = 1 ORDER BY price ASC');
        return $this->db->resultSet();
    }

    // Récupérer un plan spécifique
    public function getPlanById($id) {
        $this->db->query('SELECT * FROM subscription_plans WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Vérifier si un utilisateur a un abonnement actif
    public function hasActiveSubscription($userId) {
        $this->db->query('
            SELECT us.* 
            FROM user_subscriptions us
            WHERE us.user_id = :user_id 
            AND us.status = "active" 
            AND us.end_date > NOW()
        ');
        $this->db->bind(':user_id', $userId);
        $result = $this->db->single();
        return $result ? true : false;
    }

    // Obtenir l'abonnement actif d'un utilisateur
    public function getActiveSubscription($userId) {
        try {
            $this->db->query('
                SELECT us.*, sp.name as plan_name, sp.features as features, sp.price as plan_price
                FROM user_subscriptions us
                JOIN subscription_plans sp ON us.plan_id = sp.id
                WHERE us.user_id = :user_id 
                AND us.status = "active" 
                AND us.end_date > NOW()
                ORDER BY us.end_date DESC
                LIMIT 1
            ');
            $this->db->bind(':user_id', $userId);
            $result = $this->db->single();
            
            // Retourner false si aucun résultat trouvé
            return $result ?: false;
        } catch (Exception $e) {
            error_log('Erreur dans getActiveSubscription: ' . $e->getMessage());
            return false;
        }
    }

    // Créer un nouvel abonnement
    public function createSubscription($userId, $planId, $paymentId = null) {
        // Récupérer le plan
        $plan = $this->getPlanById($planId);
        if (!$plan) {
            return false;
        }

        // Calculer la date de fin
        $endDate = date('Y-m-d H:i:s', strtotime('+' . $plan->duration_days . ' days'));

        // Vérifier s'il existe un abonnement actif
        $activeSubscription = $this->getActiveSubscription($userId);
        
        // Si l'utilisateur a déjà un abonnement actif, étendre sa durée
        if ($activeSubscription && $activeSubscription->plan_id == $planId) {
            $this->db->query('
                UPDATE user_subscriptions 
                SET end_date = DATE_ADD(end_date, INTERVAL :duration_days DAY),
                    payment_id = :payment_id
                WHERE id = :subscription_id
            ');
            $this->db->bind(':duration_days', $plan->duration_days);
            $this->db->bind(':payment_id', $paymentId);
            $this->db->bind(':subscription_id', $activeSubscription->id);
            
            if ($this->db->execute()) {
                return $activeSubscription->id;
            }
            return false;
        }

        // Sinon, créer un nouvel abonnement
        $this->db->query('
            INSERT INTO user_subscriptions (user_id, plan_id, end_date, payment_id)
            VALUES (:user_id, :plan_id, :end_date, :payment_id)
        ');
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':plan_id', $planId);
        $this->db->bind(':end_date', $endDate);
        $this->db->bind(':payment_id', $paymentId);
        
        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    // Enregistrer une transaction de paiement
    public function recordPayment($userId, $subscriptionId, $amount, $paymentMethod, $transactionId, $status = 'completed') {
        $this->db->query('
            INSERT INTO payment_transactions 
            (user_id, subscription_id, amount, payment_method, transaction_id, status)
            VALUES 
            (:user_id, :subscription_id, :amount, :payment_method, :transaction_id, :status)
        ');
        
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':subscription_id', $subscriptionId);
        $this->db->bind(':amount', $amount);
        $this->db->bind(':payment_method', $paymentMethod);
        $this->db->bind(':transaction_id', $transactionId);
        $this->db->bind(':status', $status);
        
        return $this->db->execute();
    }

    // Récupérer l'historique de paiement d'un utilisateur
    public function getPaymentHistory($userId) {
        $this->db->query('
            SELECT pt.*, sp.name as plan_name
            FROM payment_transactions pt
            LEFT JOIN user_subscriptions us ON pt.subscription_id = us.id
            LEFT JOIN subscription_plans sp ON us.plan_id = sp.id
            WHERE pt.user_id = :user_id
            ORDER BY pt.created_at DESC
        ');
        $this->db->bind(':user_id', $userId);
        return $this->db->resultSet();
    }

    // Vérifier si l'utilisateur peut utiliser une fonctionnalité premium
    public function canUseFeature($userId, $feature) {
        // Si l'utilisateur est admin, toujours autorisé
        $this->db->query('SELECT is_admin FROM users WHERE id = :user_id');
        $this->db->bind(':user_id', $userId);
        $user = $this->db->single();
        
        if ($user && $user->is_admin) {
            return true;
        }
        
        // Vérifier si l'utilisateur a un abonnement actif
        $subscription = $this->getActiveSubscription($userId);
        if (!$subscription) {
            // Vérifier si la fonctionnalité est disponible dans le plan gratuit
            $this->db->query('SELECT features FROM subscription_plans WHERE price = 0 AND is_active = 1 LIMIT 1');
            $basicPlan = $this->db->single();
            
            if ($basicPlan) {
                $features = explode(';', $basicPlan->features);
                foreach ($features as $feat) {
                    if (stripos($feat, $feature) !== false) {
                        return true;
                    }
                }
            }
            return false;
        }
        
        // Vérifier si la fonctionnalité est disponible dans le plan actif
        $features = explode(';', $subscription->features);
        foreach ($features as $feat) {
            if (stripos($feat, $feature) !== false) {
                return true;
            }
        }
        return false;
    }

    // Récupérer tous les abonnements pour l'admin
    public function getAllSubscriptions($limit = 20, $offset = 0) {
        $this->db->query('
            SELECT us.*, sp.name as plan_name, sp.price as plan_price,
                   u.first_name, u.last_name, u.email
            FROM user_subscriptions us
            JOIN subscription_plans sp ON us.plan_id = sp.id
            JOIN users u ON us.user_id = u.id
            ORDER BY us.created_at DESC
            LIMIT :limit OFFSET :offset
        ');
        
        $this->db->bind(':limit', $limit);
        $this->db->bind(':offset', $offset);
        
        return $this->db->resultSet();
    }

    // Compter les abonnements actifs
    public function getActiveSubscriptions() {
        $this->db->query('SELECT COUNT(*) as count FROM user_subscriptions WHERE status = "active" AND end_date > NOW()');
        $result = $this->db->single();
        return $result->count ?? 0;
    }

    // Revenus du mois
    public function getRevenueThisMonth() {
        $this->db->query('
            SELECT SUM(amount) as revenue 
            FROM payment_transactions 
            WHERE status = "completed" 
            AND MONTH(created_at) = MONTH(NOW()) 
            AND YEAR(created_at) = YEAR(NOW())
        ');
        
        $result = $this->db->single();
        return $result->revenue ?? 0;
    }

    // Revenus des 12 derniers mois
    public function getRevenueLast12Months() {
        $this->db->query('
            SELECT 
                YEAR(created_at) as year,
                MONTH(created_at) as month,
                SUM(amount) as revenue
            FROM payment_transactions 
            WHERE status = "completed" 
            AND created_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
            GROUP BY YEAR(created_at), MONTH(created_at)
            ORDER BY year ASC, month ASC
        ');
        
        return $this->db->resultSet();
    }

    // Abonnements récents
    public function getRecentSubscriptions($limit = 5) {
        $this->db->query('
            SELECT us.*, sp.name as plan_name, u.first_name, u.last_name
            FROM user_subscriptions us
            JOIN subscription_plans sp ON us.plan_id = sp.id
            JOIN users u ON us.user_id = u.id
            ORDER BY us.created_at DESC
            LIMIT :limit
        ');
        
        $this->db->bind(':limit', $limit);
        
        return $this->db->resultSet();
    }

    // Compter le total des abonnements
    public function getTotalSubscriptions() {
        $this->db->query('SELECT COUNT(*) as count FROM user_subscriptions');
        $result = $this->db->single();
        return $result->count ?? 0;
    }

    // Statistiques des abonnements
    public function getSubscriptionStatistics() {
        $this->db->query('
            SELECT 
                COUNT(*) as total_subscriptions,
                SUM(CASE WHEN status = "active" AND end_date > NOW() THEN 1 ELSE 0 END) as active_subscriptions,
                SUM(CASE WHEN status = "expired" THEN 1 ELSE 0 END) as expired_subscriptions,
                SUM(CASE WHEN status = "cancelled" THEN 1 ELSE 0 END) as cancelled_subscriptions,
                AVG(DATEDIFF(end_date, start_date)) as avg_subscription_duration
            FROM user_subscriptions
        ');
        
        return $this->db->single();
    }

    // Revenus du jour
    public function getRevenueToday() {
        $this->db->query('
            SELECT COALESCE(SUM(amount), 0) as revenue 
            FROM payment_transactions 
            WHERE status = "completed" AND DATE(created_at) = CURDATE()
        ');
        
        $result = $this->db->single();
        return $result ? $result->revenue : 0;
    }

    // Revenus totaux
    public function getTotalRevenue() {
        $this->db->query('
            SELECT COALESCE(SUM(amount), 0) as revenue 
            FROM payment_transactions 
            WHERE status = "completed"
        ');
        
        $result = $this->db->single();
        return $result ? $result->revenue : 0;
    }

    // Revenus des 30 derniers jours
    public function getRevenueLast30Days() {
        $this->db->query('
            SELECT DATE(created_at) as date, COALESCE(SUM(amount), 0) as revenue
            FROM payment_transactions 
            WHERE status = "completed" 
            AND created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
            GROUP BY DATE(created_at)
            ORDER BY date ASC
        ');
        
        return $this->db->resultSet();
    }

    // Plan le plus vendu
    public function getTopSellingPlan() {
        $this->db->query('
            SELECT sp.name, COUNT(us.id) as sales
            FROM subscription_plans sp
            JOIN user_subscriptions us ON sp.id = us.plan_id
            WHERE sp.price > 0
            GROUP BY sp.id, sp.name
            ORDER BY sales DESC
            LIMIT 1
        ');
        
        $result = $this->db->single();
        return $result ? $result->name : 'Aucun';
    }

    // Abonnements par plan
    public function getSubscriptionsByPlan() {
        $this->db->query('
            SELECT sp.name, COUNT(us.id) as count
            FROM subscription_plans sp
            LEFT JOIN user_subscriptions us ON sp.id = us.plan_id AND us.status = "active"
            GROUP BY sp.id, sp.name
            ORDER BY count DESC
        ');
        
        return $this->db->resultSet();
    }

    // Revenus mensuels
    public function getMonthlyRevenue() {
        $this->db->query('
            SELECT 
                YEAR(created_at) as year,
                MONTH(created_at) as month,
                COALESCE(SUM(amount), 0) as revenue
            FROM payment_transactions 
            WHERE status = "completed" 
            AND created_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
            GROUP BY YEAR(created_at), MONTH(created_at)
            ORDER BY year DESC, month DESC
        ');
        
        return $this->db->resultSet();
    }

    // Revenus quotidiens
    public function getDailyRevenue() {
        $this->db->query('
            SELECT 
                DATE(created_at) as date,
                COALESCE(SUM(amount), 0) as revenue
            FROM payment_transactions 
            WHERE status = "completed" 
            AND created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
            GROUP BY DATE(created_at)
            ORDER BY date DESC
        ');
        
        return $this->db->resultSet();
    }

    // Revenus par plan
    public function getRevenueByPlan() {
        $this->db->query('
            SELECT 
                sp.name, 
                COALESCE(SUM(pt.amount), 0) as revenue, 
                COUNT(pt.id) as transactions
            FROM subscription_plans sp
            LEFT JOIN user_subscriptions us ON sp.id = us.plan_id
            LEFT JOIN payment_transactions pt ON us.id = pt.subscription_id AND pt.status = "completed"
            GROUP BY sp.id, sp.name
            ORDER BY revenue DESC
        ');
        
        return $this->db->resultSet();
    }

    // Transactions récentes
    public function getRecentTransactions($limit = 20) {
        $this->db->query('
            SELECT 
                pt.*, 
                u.first_name, 
                u.last_name, 
                u.email, 
                sp.name as plan_name
            FROM payment_transactions pt
            JOIN user_subscriptions us ON pt.subscription_id = us.id
            JOIN users u ON us.user_id = u.id
            JOIN subscription_plans sp ON us.plan_id = sp.id
            WHERE pt.status = "completed"
            ORDER BY pt.created_at DESC
            LIMIT :limit
        ');
        
        $this->db->bind(':limit', $limit);
        return $this->db->resultSet();
    }

    // Meilleurs clients
    public function getTopCustomers($limit = 10) {
        $this->db->query('
            SELECT 
                u.first_name, 
                u.last_name, 
                u.email, 
                COALESCE(SUM(pt.amount), 0) as total_spent,
                COUNT(pt.id) as transaction_count
            FROM users u
            JOIN user_subscriptions us ON u.id = us.user_id
            JOIN payment_transactions pt ON us.id = pt.subscription_id
            WHERE pt.status = "completed"
            GROUP BY u.id
            ORDER BY total_spent DESC
            LIMIT :limit
        ');
        
        $this->db->bind(':limit', $limit);
        return $this->db->resultSet();
    }
}
?>
