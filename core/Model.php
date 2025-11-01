&lt;?php
/**
 * Base Model Class
 * Class cha cho t?t c? models
 */

class Model {
    protected $db;
    protected $table;
    protected $primaryKey = 'id';
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * T?m theo ID
     */
    public function find($id) {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id LIMIT 1";
        return $this->db->fetchOne($sql, ['id' => $id]);
    }
    
    /**
     * L?y t?t c?
     */
    public function all($orderBy = null, $limit = null) {
        $sql = "SELECT * FROM {$this->table}";
        
        if ($orderBy) {
            $sql .= " ORDER BY {$orderBy}";
        }
        
        if ($limit) {
            $sql .= " LIMIT {$limit}";
        }
        
        return $this->db->fetchAll($sql);
    }
    
    /**
     * T?m v?i ?i?u ki?n
     */
    public function where($conditions, $params = [], $orderBy = null, $limit = null) {
        $sql = "SELECT * FROM {$this->table} WHERE {$conditions}";
        
        if ($orderBy) {
            $sql .= " ORDER BY {$orderBy}";
        }
        
        if ($limit) {
            $sql .= " LIMIT {$limit}";
        }
        
        return $this->db->fetchAll($sql, $params);
    }
    
    /**
     * T?m m?t b?n ghi v?i ?i?u ki?n
     */
    public function findWhere($conditions, $params = []) {
        $sql = "SELECT * FROM {$this->table} WHERE {$conditions} LIMIT 1";
        return $this->db->fetchOne($sql, $params);
    }
    
    /**
     * T?o m?i
     */
    public function create($data) {
        return $this->db->insert($this->table, $data);
    }
    
    /**
     * C?p nh?t
     */
    public function update($id, $data) {
        return $this->db->update(
            $this->table,
            $data,
            "{$this->primaryKey} = :id",
            ['id' => $id]
        );
    }
    
    /**
     * X?a
     */
    public function delete($id) {
        return $this->db->delete(
            $this->table,
            "{$this->primaryKey} = :id",
            ['id' => $id]
        );
    }
    
    /**
     * ??m
     */
    public function count($where = '1=1', $params = []) {
        return $this->db->count($this->table, $where, $params);
    }
    
    /**
     * T?m ki?m
     */
    public function search($field, $keyword, $limit = null) {
        $sql = "SELECT * FROM {$this->table} WHERE {$field} LIKE :keyword";
        
        if ($limit) {
            $sql .= " LIMIT {$limit}";
        }
        
        return $this->db->fetchAll($sql, ['keyword' => "%{$keyword}%"]);
    }
    
    /**
     * Ph?n trang
     */
    public function paginate($page = 1, $perPage = 10, $where = '1=1', $params = [], $orderBy = null) {
        $offset = ($page - 1) * $perPage;
        
        $sql = "SELECT * FROM {$this->table} WHERE {$where}";
        
        if ($orderBy) {
            $sql .= " ORDER BY {$orderBy}";
        }
        
        $sql .= " LIMIT {$perPage} OFFSET {$offset}";
        
        $items = $this->db->fetchAll($sql, $params);
        $total = $this->count($where, $params);
        
        return [
            'items' => $items,
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage,
            'total_pages' => ceil($total / $perPage)
        ];
    }
    
    /**
     * Query t?y ch?nh
     */
    public function query($sql, $params = []) {
        return $this->db->query($sql, $params);
    }
    
    /**
     * Fetch all v?i query t?y ch?nh
     */
    public function fetchAll($sql, $params = []) {
        return $this->db->fetchAll($sql, $params);
    }
    
    /**
     * Fetch one v?i query t?y ch?nh
     */
    public function fetchOne($sql, $params = []) {
        return $this->db->fetchOne($sql, $params);
    }
}
