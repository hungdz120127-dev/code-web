&lt;?php
/**
 * Lesson Model
 */

class Lesson extends Model {
    protected $table = 'lessons';
    
    /**
     * L?y b?i h?c theo kh?a h?c
     */
    public function getByCourse($courseId) {
        $sql = "SELECT l.*, c.title as chapter_title, c.position as chapter_position
                FROM {$this->table} l
                LEFT JOIN chapters c ON l.chapter_id = c.id
                WHERE l.course_id = :cid
                ORDER BY c.position ASC, l.position ASC";
        
        return $this->fetchAll($sql, ['cid' => $courseId]);
    }
    
    /**
     * L?y b?i h?c theo chapter
     */
    public function getByChapter($chapterId) {
        return $this->where('chapter_id = :cid', ['cid' => $chapterId], 'position ASC');
    }
    
    /**
     * L?y b?i h?c v?i ti?n ?? c?a user
     */
    public function getWithProgress($lessonId, $userId) {
        $sql = "SELECT l.*, 
                (SELECT is_completed FROM lesson_progress WHERE lesson_id = l.id AND user_id = :uid) as is_completed
                FROM {$this->table} l
                WHERE l.id = :lid";
        
        return $this->fetchOne($sql, ['lid' => $lessonId, 'uid' => $userId]);
    }
    
    /**
     * L?y b?i h?c k? ti?p
     */
    public function getNext($courseId, $currentPosition) {
        $sql = "SELECT * FROM {$this->table} 
                WHERE course_id = :cid AND position > :pos 
                ORDER BY position ASC 
                LIMIT 1";
        
        return $this->fetchOne($sql, ['cid' => $courseId, 'pos' => $currentPosition]);
    }
    
    /**
     * L?y b?i h?c tr??c ??
     */
    public function getPrevious($courseId, $currentPosition) {
        $sql = "SELECT * FROM {$this->table} 
                WHERE course_id = :cid AND position < :pos 
                ORDER BY position DESC 
                LIMIT 1";
        
        return $this->fetchOne($sql, ['cid' => $courseId, 'pos' => $currentPosition]);
    }
    
    /**
     * ??nh d?u ho?n th?nh
     */
    public function markComplete($lessonId, $userId) {
        $sql = "INSERT INTO lesson_progress (user_id, lesson_id, is_completed, completed_at) 
                VALUES (:uid, :lid, 1, NOW())
                ON DUPLICATE KEY UPDATE is_completed = 1, completed_at = NOW()";
        
        return $this->query($sql, ['uid' => $userId, 'lid' => $lessonId]);
    }
    
    /**
     * Ki?m tra ?? ho?n th?nh ch?a
     */
    public function isCompleted($lessonId, $userId) {
        $sql = "SELECT is_completed FROM lesson_progress 
                WHERE lesson_id = :lid AND user_id = :uid";
        
        $result = $this->fetchOne($sql, ['lid' => $lessonId, 'uid' => $userId]);
        return $result ? (bool)$result['is_completed'] : false;
    }
    
    /**
     * ??m t?ng s? b?i h?c trong kh?a
     */
    public function countByCourse($courseId) {
        return $this->count('course_id = :cid', ['cid' => $courseId]);
    }
    
    /**
     * ??m s? b?i ?? ho?n th?nh
     */
    public function countCompleted($courseId, $userId) {
        $sql = "SELECT COUNT(*) as total FROM lesson_progress lp
                INNER JOIN lessons l ON lp.lesson_id = l.id
                WHERE l.course_id = :cid AND lp.user_id = :uid AND lp.is_completed = 1";
        
        $result = $this->fetchOne($sql, ['cid' => $courseId, 'uid' => $userId]);
        return $result ? $result['total'] : 0;
    }
}
