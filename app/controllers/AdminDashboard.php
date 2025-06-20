<?php
class AdminDashboard extends Controller {
    private $userModel;
    private $subscriptionModel;
    private $reportModel;

    public function __construct() {
      
        if(!isset($_SESSION['admin_id'])) {
            redirect('adminAuth');
        }
        
        $this->userModel = $this->model('User');
        $this->subscriptionModel = $this->model('Subscription');
        
        
        $user = $this->userModel->getUserById($_SESSION['admin_id']);
        if(!$user || !$user->is_admin) {
            unset($_SESSION['admin_id']);
            flash('admin_error', 'Accès refusé. Vous devez être administrateur.', 'alert-danger');
            redirect('adminAuth');
        }
        
        if(file_exists(APPROOT . '/models/Report.php')) {
            $this->reportModel = $this->model('Report');
        }
    }

    public function index() {
        
        $stats = [
            'total_users' => $this->userModel->getTotalUsers(),
            'new_users_today' => $this->userModel->getNewUsersToday(),
            'new_users_this_month' => $this->userModel->getNewUsersThisMonth(),
            'active_subscriptions' => $this->subscriptionModel->getActiveSubscriptions(),
            'revenue_today' => $this->subscriptionModel->getRevenueToday(),
            'revenue_this_month' => $this->subscriptionModel->getRevenueThisMonth(),
            'revenue_total' => $this->subscriptionModel->getTotalRevenue(),
            'banned_users' => $this->userModel->getBannedUsersCount(),
            'top_selling_plan' => $this->subscriptionModel->getTopSellingPlan()
        ];

        
        $chartData = [
            'revenue_last_30_days' => $this->subscriptionModel->getRevenueLast30Days(),
            'users_last_30_days' => $this->userModel->getUsersLast30Days(),
            'subscriptions_by_plan' => $this->subscriptionModel->getSubscriptionsByPlan()
        ];

        
        $recentActivities = [
            'recent_users' => $this->userModel->getRecentUsers(5),
            'recent_subscriptions' => $this->subscriptionModel->getRecentSubscriptions(5)
        ];

        $data = [
            'title' => 'Administration - Tableau de bord',
            'stats' => $stats,
            'chart_data' => $chartData,
            'recent_activities' => $recentActivities
        ];

        $this->view('admin/dashboard', $data);
    }

    
    public function users($page = 1) {
        $limit = 20;
        $offset = ($page - 1) * $limit;
        
        $users = $this->userModel->getAllUsersForAdmin($limit, $offset);
        $totalUsers = $this->userModel->getTotalUsers();
        $totalPages = ceil($totalUsers / $limit);

        $data = [
            'title' => 'Administration - Gestion des utilisateurs',
            'users' => $users,
            'current_page' => $page,
            'total_pages' => $totalPages,
            'total_users' => $totalUsers
        ];

        $this->view('admin/users', $data);
    }

  
    public function subscriptions() {
        $subscriptions = $this->subscriptionModel->getAllSubscriptions();

        $data = [
            'title' => 'Administration - Gestion des abonnements',
            'subscriptions' => $subscriptions
        ];

        $this->view('admin/subscriptions', $data);
    }

    public function revenue() {
        $revenueData = [
            'daily_revenue' => $this->subscriptionModel->getDailyRevenue(),
            'monthly_revenue' => $this->subscriptionModel->getMonthlyRevenue(),
            'revenue_by_plan' => $this->subscriptionModel->getRevenueByPlan(),
            'recent_transactions' => $this->subscriptionModel->getRecentTransactions(20),
            'top_customers' => $this->subscriptionModel->getTopCustomers(10)
        ];

        $data = [
            'title' => 'Administration - Gestion des revenus',
            'revenue_data' => $revenueData
        ];

        $this->view('admin/revenue', $data);
    }

   
    public function getStats() {
        header('Content-Type: application/json');
        
        $stats = [
            'total_users' => $this->userModel->getTotalUsers(),
            'new_users_today' => $this->userModel->getNewUsersToday(),
            'active_subscriptions' => $this->subscriptionModel->getActiveSubscriptions(),
            'revenue_today' => $this->subscriptionModel->getRevenueToday(),
            'revenue_this_month' => $this->subscriptionModel->getRevenueThisMonth()
        ];
        
        echo json_encode($stats);
    }

 
    public function banUser($userId) {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $reason = isset($_POST['ban_reason']) ? trim($_POST['ban_reason']) : '';
            
           
            $targetUser = $this->userModel->getUserById($userId);
            if($targetUser && $targetUser->is_admin) {
                flash('admin_error', 'Impossible de bannir un administrateur', 'alert-danger');
                redirect('adminDashboard/users');
                return;
            }
            
            $result = $this->userModel->banUser($userId, $reason);
            
            if($result) {
                flash('admin_success', 'Utilisateur banni avec succès', 'alert-success');
            } else {
                flash('admin_error', 'Erreur lors du bannissement', 'alert-danger');
            }
        }
        
        redirect('adminDashboard/users');
    }

    public function toggleUserStatus($userId) {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            $targetUser = $this->userModel->getUserById($userId);
            if($targetUser && $targetUser->is_admin) {
                flash('admin_error', 'Impossible de suspendre un administrateur', 'alert-danger');
                redirect('adminDashboard/users');
                return;
            }
            
            $result = $this->userModel->toggleUserStatus($userId);
            
            if($result) {
                $status = $targetUser && $targetUser->is_suspended ? 'réactivé' : 'suspendu';
                flash('admin_success', 'Utilisateur ' . $status . ' avec succès', 'alert-success');
            } else {
                flash('admin_error', 'Erreur lors de la mise à jour du statut', 'alert-danger');
            }
        }
        
        redirect('adminDashboard/users');
    }

    public function unbanUser($userId) {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            $targetUser = $this->userModel->getUserById($userId);
            if(!$targetUser) {
                flash('admin_error', 'Utilisateur introuvable', 'alert-danger');
                redirect('adminDashboard/users');
                return;
            }
            
            $result = $this->userModel->unbanUser($userId);
            
            if($result) {
                flash('admin_success', 'Utilisateur débanni avec succès', 'alert-success');
            } else {
                flash('admin_error', 'Erreur lors du débannissement', 'alert-danger');
            }
        }
        
        redirect('adminDashboard/users');
    }

  
    public function forceLogout($userId) {
        
        $user = $this->userModel->getUserById($userId);
        if($user && $user->is_banned) {
            flash('admin_success', 'L\'utilisateur ne pourra plus se connecter', 'alert-success');
        }
        
        redirect('adminDashboard/users');
    }
}
?>
