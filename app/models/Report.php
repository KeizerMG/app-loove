<?php
class Report {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // Créer un signalement
    public function createReport($reporterId, $reportedUserId, $reason, $description = '') {
        $this->db->query('INSERT INTO reports (reporter_id, reported_user_id, reason, description) VALUES (:reporter_id, :reported_user_id, :reason, :description)');
        
        $this->db->bind(':reporter_id', $reporterId);
        $this->db->bind(':reported_user_id', $reportedUserId);
        $this->db->bind(':reason', $reason);
        $this->db->bind(':description', $description);
        
        return $this->db->execute();
    }

    // Récupérer tous les signalements
    public function getAllReports($limit = 20, $offset = 0) {
        $this->db->query('
            SELECT r.*, 
                   u1.first_name as reporter_name, u1.last_name as reporter_lastname, u1.email as reporter_email,
                   u2.first_name as reported_name, u2.last_name as reported_lastname, u2.email as reported_email,
                   u2.profile_pic as reported_profile_pic,
                   admin.first_name as admin_name
            FROM reports r
            JOIN users u1 ON r.reporter_id = u1.id
            JOIN users u2 ON r.reported_user_id = u2.id
            LEFT JOIN users admin ON r.processed_by = admin.id
            ORDER BY r.created_at DESC
            LIMIT :limit OFFSET :offset
        ');
        
        $this->db->bind(':limit', $limit);
        $this->db->bind(':offset', $offset);
        
        return $this->db->resultSet();
    }

    // Compter les signalements en attente
    public function getPendingReportsCount() {
        $this->db->query('SELECT COUNT(*) as count FROM reports WHERE status = "pending"');
        $result = $this->db->single();
        return $result->count ?? 0;
    }

    // Récupérer les signalements récents
    public function getRecentReports($limit = 5) {
        $this->db->query('
            SELECT r.*, 
                   u1.first_name as reporter_name,
                   u2.first_name as reported_name
            FROM reports r
            JOIN users u1 ON r.reporter_id = u1.id
            JOIN users u2 ON r.reported_user_id = u2.id
            WHERE r.status = "pending"
            ORDER BY r.created_at DESC
            LIMIT :limit
        ');
        
        $this->db->bind(':limit', $limit);
        return $this->db->resultSet();
    }

    // Traiter un signalement
    public function processReport($reportId, $action, $notes, $adminId) {
        $status = '';
        switch($action) {
            case 'approve':
                $status = 'approved';
                break;
            case 'reject':
                $status = 'rejected';
                break;
            default:
                return false;
        }

        $this->db->query('
            UPDATE reports 
            SET status = :status, admin_notes = :notes, processed_by = :admin_id, processed_at = NOW() 
            WHERE id = :id
        ');
        
        $this->db->bind(':status', $status);
        $this->db->bind(':notes', $notes);
        $this->db->bind(':admin_id', $adminId);
        $this->db->bind(':id', $reportId);
        
        return $this->db->execute();
    }

    // Récupérer un signalement par ID
    public function getReportById($reportId) {
        $this->db->query('SELECT * FROM reports WHERE id = :id');
        $this->db->bind(':id', $reportId);
        return $this->db->single();
    }

    // Statistiques des signalements
    public function getReportStatistics() {
        $this->db->query('
            SELECT 
                COUNT(*) as total_reports,
                SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pending_reports,
                SUM(CASE WHEN status = "approved" THEN 1 ELSE 0 END) as approved_reports,
                SUM(CASE WHEN status = "rejected" THEN 1 ELSE 0 END) as rejected_reports,
                SUM(CASE WHEN created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY) THEN 1 ELSE 0 END) as reports_last_week
            FROM reports
        ');
        
        return $this->db->single();
    }

    // Compter le total des signalements
    public function getTotalReports() {
        $this->db->query('SELECT COUNT(*) as count FROM reports');
        $result = $this->db->single();
        return $result->count ?? 0;
    }
}
