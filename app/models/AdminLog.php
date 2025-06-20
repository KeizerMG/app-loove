<?php
class AdminLog {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // Enregistrer une action admin
    public function logAction($adminId, $action, $targetType, $targetId = null, $details = null) {
        $this->db->query('
            INSERT INTO admin_logs (admin_id, action, target_type, target_id, details, ip_address) 
            VALUES (:admin_id, :action, :target_type, :target_id, :details, :ip_address)
        ');
        
        $this->db->bind(':admin_id', $adminId);
        $this->db->bind(':action', $action);
        $this->db->bind(':target_type', $targetType);
        $this->db->bind(':target_id', $targetId);
        $this->db->bind(':details', $details);
        $this->db->bind(':ip_address', $_SERVER['REMOTE_ADDR'] ?? null);
        
        return $this->db->execute();
    }

    // Récupérer tous les logs
    public function getAllLogs($limit = 50, $offset = 0) {
        $this->db->query('
            SELECT al.*, u.first_name, u.last_name, u.email
            FROM admin_logs al
            JOIN users u ON al.admin_id = u.id
            ORDER BY al.created_at DESC
            LIMIT :limit OFFSET :offset
        ');
        
        $this->db->bind(':limit', $limit);
        $this->db->bind(':offset', $offset);
        
        return $this->db->resultSet();
    }

    // Compter le total des logs
    public function getTotalLogs() {
        $this->db->query('SELECT COUNT(*) as count FROM admin_logs');
        $result = $this->db->single();
        return $result->count ?? 0;
    }
}
