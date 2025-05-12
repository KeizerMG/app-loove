<?php
require_once 'utils/Database.php';

abstract class Model {
    protected $db;
    protected $table;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function getById($id) {
        return $this->db->fetch("SELECT * FROM {$this->table} WHERE id = :id", ['id' => $id]);
    }
    
    public function getAll($limit = null, $offset = null) {
        $sql = "SELECT * FROM {$this->table}";
        
        $params = [];
        if ($limit !== null) {
            $sql .= " LIMIT :limit";
            $params['limit'] = $limit;
            
            if ($offset !== null) {
                $sql .= " OFFSET :offset";
                $params['offset'] = $offset;
            }
        }
        
        return $this->db->fetchAll($sql, $params);
    }
    
    public function create($data) {
        return $this->db->insert($this->table, $data);
    }
    
    public function update($id, $data) {
        return $this->db->update($this->table, $data, "id = :id", ['id' => $id]);
    }
    
    public function delete($id) {
        return $this->db->delete($this->table, "id = :id", ['id' => $id]);
    }
    
    public function count($where = '', $params = []) {
        $sql = "SELECT COUNT(*) as count FROM {$this->table}";
        
        if (!empty($where)) {
            $sql .= " WHERE $where";
        }
        
        $result = $this->db->fetch($sql, $params);
        return $result ? $result['count'] : 0;
    }
}
?>
