&lt;?php
/**
 * Course Model
 */

class Course extends Model {
    protected $table = 'courses';
    
    /**
     * L?y kh?a h?c v?i th?ng tin gi?o vi?n
     */
    public function getWithTeacher($courseId) {
        $sql = "SELECT c.*, u.name as teacher_name, u.avatar as teacher_avatar, u.bio as teacher_bio
                FROM {$this->table} c
                LEFT JOIN users u ON c.teacher_id = u.id
                WHERE c.id = :id";
        return $this->fetchOne($sql, ['id' => $courseId]);
    }
    
    /**
     * L?y t?t c? kh?a h?c ?? publish
     */
    public function getPublished($limit = null) {
        $sql = "SELECT c.*, u.name as teacher_name 
                FROM {$this->table} c
                LEFT JOIN users u ON c.teacher_id = u.id
                WHERE c.is_published = 1
                ORDER BY c.created_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT {$limit}";
        }
        
        return $this->fetchAll($sql);
    }
    
    /**
     * L?y kh?a h?c theo gi?o vi?n
     */
    public function getByTeacher($teacherId) {
        $sql = "SELECT * FROM {$this->table} 
                WHERE teacher_id = :tid 
                ORDER BY created_at DESC";
        return $this->fetchAll($sql, ['tid' => $teacherId]);
    }
    
    /**
     * L?y kh?a h?c theo category
     */
    public function getByCategory($category, $limit = null) {
        $sql = "SELECT c.*, u.name as teacher_name 
                FROM {$this->table} c
                LEFT JOIN users u ON c.teacher_id = u.id
                WHERE c.category = :cat AND c.is_published = 1
                ORDER BY c.created_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT {$limit}";
        }
        
        return $this->fetchAll($sql, ['cat' => $category]);
    }
    
    /**
     * T?m ki?m kh?a h?c
     */
    public function searchCourses($keyword, $limit = 20) {
        $sql = "SELECT c.*, u.name as teacher_name 
                FROM {$this->table} c
                LEFT JOIN users u ON c.teacher_id = u.id
                WHERE c.is_published = 1 
                AND (c.title LIKE :kw OR c.description LIKE :kw OR c.category LIKE :kw)
                ORDER BY c.view_count DESC
                LIMIT {$limit}";
        
        return $this->fetchAll($sql, ['kw' => "%{$keyword}%"]);
    }
    
    /**
     * L?y kh?a h?c ph? bi?n
     */
    public function getPopular($limit = 6) {
        $sql = "SELECT c.*, u.name as teacher_name,
                (SELECT COUNT(*) FROM enrollments WHERE course_id = c.id) as student_count
                FROM {$this->table} c
                LEFT JOIN users u ON c.teacher_id = u.id
                WHERE c.is_published = 1
                ORDER BY c.view_count DESC, student_count DESC
                LIMIT {$limit}";
        
        return $this->fetchAll($sql);
    }
    
    /**
     * T?ng view count
     */
    public function incrementView($courseId) {
        $sql = "UPDATE {$this->table} SET view_count = view_count + 1 WHERE id = :id";
        return $this->query($sql, ['id' => $courseId]);
    }
    
    /**
     * L?y s? l??ng h?c vi?n
     */
    public function getStudentCount($courseId) {
        $sql = "SELECT COUNT(*) as total FROM enrollments WHERE course_id = :cid";
        $result = $this->fetchOne($sql, ['cid' => $courseId]);
        return $result ? $result['total'] : 0;
    }
    
    /**
     * L?y rating trung b?nh
     */
    public function getAverageRating($courseId) {
        $sql = "SELECT AVG(rating) as avg_rating, COUNT(*) as total_reviews 
                FROM course_reviews 
                WHERE course_id = :cid";
        return $this->fetchOne($sql, ['cid' => $courseId]);
    }
    
    /**
     * T?o slug t? title
     */
    public function createSlug($title) {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
        
        // Check if slug exists
        $exists = $this->findWhere('slug = :slug', ['slug' => $slug]);
        if ($exists) {
            $slug .= '-' . time();
        }
        
        return $slug;
    }
    
    /**
     * L?y kh?a h?c ???c ?? xu?t cho user
     */
    public function getRecommended($userId, $limit = 4) {
        $sql = "SELECT c.*, u.name as teacher_name,
                (SELECT COUNT(*) FROM enrollments WHERE course_id = c.id) as student_count
                FROM {$this->table} c
                LEFT JOIN users u ON c.teacher_id = u.id
                WHERE c.is_published = 1
                AND c.id NOT IN (SELECT course_id FROM enrollments WHERE user_id = :uid)
                ORDER BY c.rating DESC, student_count DESC
                LIMIT {$limit}";
        
        return $this->fetchAll($sql, ['uid' => $userId]);
    }
}
