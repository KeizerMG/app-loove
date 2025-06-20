<?php
class Admin extends Controller {
    private $userModel;
    private $subscriptionModel;
    private $reportModel;
    private $adminLogModel;

    public function __construct() {
        if(!isLoggedIn()) {
            redirect('users/login');
        }
        
        $this->userModel = $this->model('User');
        
       
        $user = $this->userModel->getUserById($_SESSION['user_id']);
        if(!$user || !$user->is_admin) {
            flash('access_denied', 'Accès refusé. Vous devez être administrateur.', 'alert-loove-danger');
            redirect('pages/index');
        }
        
        $this->subscriptionModel = $this->model('Subscription');
        if(file_exists(APPROOT . '/models/Report.php')) {
            $this->reportModel = $this->model('Report');
        }
        if(file_exists(APPROOT . '/models/AdminLog.php')) {
            $this->adminLogModel = $this->model('AdminLog');
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
            'pending_reports' => 0,
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
            'recent_subscriptions' => $this->subscriptionModel->getRecentSubscriptions(5),
            'recent_reports' => []
        ];

        $data = [
            'title' => 'Tableau de bord Admin',
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
            'title' => 'Gestion des utilisateurs',
            'users' => $users,
            'current_page' => $page,
            'total_pages' => $totalPages,
            'total_users' => $totalUsers
        ];

        $this->view('admin/users', $data);
    }

   
    public function toggleUserStatus($userId) {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $result = $this->userModel->toggleUserStatus($userId);
            
            if($result) {
                flash('admin_success', 'Statut utilisateur mis à jour', 'alert-loove-success');
            } else {
                flash('admin_error', 'Erreur lors de la mise à jour', 'alert-loove-danger');
            }
        }
        
        redirect('admin/users');
    }


    public function banUser($userId) {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $reason = $_POST['ban_reason'] ?? '';
            $result = $this->userModel->banUser($userId, $reason);
            
            if($result) {
                $this->adminLogModel->logAction($_SESSION['user_id'], 'ban_user', 'user', $userId, 'Utilisateur banni: ' . $reason);
                flash('admin_success', 'Utilisateur banni avec succès', 'alert-loove-success');
            } else {
                flash('admin_error', 'Erreur lors du bannissement', 'alert-loove-danger');
            }
        }
        
        redirect('admin/users');
    }

   
    public function reports($page = 1) {
        $limit = 20;
        $offset = ($page - 1) * $limit;
        
        $reports = $this->reportModel->getAllReports($limit, $offset);
        $totalReports = $this->reportModel->getTotalReports();
        $totalPages = ceil($totalReports / $limit);

        $data = [
            'title' => 'Gestion des signalements',
            'reports' => $reports,
            'current_page' => $page,
            'total_pages' => $totalPages,
            'total_reports' => $totalReports
        ];

        $this->view('admin/reports', $data);
    }
    public function processReport($reportId) {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $action = $_POST['action'] ?? '';
            $notes = $_POST['admin_notes'] ?? '';
            
            $result = $this->reportModel->processReport($reportId, $action, $notes, $_SESSION['user_id']);
            
            if($result) {
                $this->adminLogModel->logAction($_SESSION['user_id'], 'process_report', 'report', $reportId, 'Action: ' . $action);
                flash('admin_success', 'Signalement traité avec succès', 'alert-loove-success');
            } else {
                flash('admin_error', 'Erreur lors du traitement', 'alert-loove-danger');
            }
        }
        
        redirect('admin/reports');
    }

    
    public function logs($page = 1) {
        $limit = 50;
        $offset = ($page - 1) * $limit;
        
        $logs = $this->adminLogModel->getAllLogs($limit, $offset);
        $totalLogs = $this->adminLogModel->getTotalLogs();
        $totalPages = ceil($totalLogs / $limit);

        $data = [
            'title' => 'Logs d\'activité',
            'logs' => $logs,
            'current_page' => $page,
            'total_pages' => $totalPages,
            'total_logs' => $totalLogs
        ];

        $this->view('admin/logs', $data);
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
            'title' => 'Gestion des revenus',
            'revenue_data' => $revenueData
        ];

        $this->view('admin/revenue', $data);
    }
}
?>
