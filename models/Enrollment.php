&lt;?php
/**
 * Enrollment Model
 */

class Enrollment extends Model {
    protected $table = 'enrollments';
    
    /**
     * ??ng k? kh?a h?c
     */
    public function enroll($userId, $courseId) {
        // Check if already enrolled
        if ($this->isEnrolled($userId, $courseId)) {
            return ['success' => false, 'message' => 'B?n ?? ??ng k? kh?a h?c n?y'];
        }
        
        $result = $this->create([
            'user_id' => $userId,
            'course_id' => $courseId,
            'progress' => 0
        ]);
        
        if ($result) {
            return ['success' => true, 'message' => '??ng k? kh?a h?c th?nh c?ng'];
        }
        
        return ['success' => false, 'message' => 'C? l?i x?y ra'];
    }
    
    /**
     * Ki?m tra ?? ??ng k? ch?a
     */
    public function isEnrolled($userId, $courseId) {
        $result = $this->findWhere('user_id = :uid AND course_id = :cid', [
            'uid' => $userId,
            'cid' => $courseId
        ]);
        
        return $result !== null;
    }
    
    /**
     * L?y kh?a h?c c?a user
     */
    public function getUserCourses($userId) {
        $sql = "SELECT e.*, c.title, c.thumbnail, c.description, 
                u.name as teacher_name,
                (SELECT COUNT(*) FROM lessons WHERE course_id = c.id) as total_lessons,
                (SELECT COUNT(*) FROM lesson_progress lp 
                 INNER JOIN lessons l ON lp.lesson_id = l.id 
                 WHERE l.course_id = c.id AND lp.user_id = e.user_id AND lp.is_completed = 1) as completed_lessons
                FROM {$this->table} e
                INNER JOIN courses c ON e.course_id = c.id
                LEFT JOIN users u ON c.teacher_id = u.id
                WHERE e.user_id = :uid
                ORDER BY e.enrolled_at DESC";
        
        return $this->fetchAll($sql, ['uid' => $userId]);
    }
    
    /**
     * C?p nh?t ti?n ??
     */
    public function updateProgress($userId, $courseId) {
        // Get total lessons
        $totalSql = "SELECT COUNT(*) as total FROM lessons WHERE course_id = :cid";
        $totalResult = $this->fetchOne($totalSql, ['cid' => $courseId]);
        $totalLessons = $totalResult['total'];
        
        if ($totalLessons == 0) {
            return false;
        }
        
        // Get completed lessons
        $completedSql = "SELECT COUNT(*) as total FROM lesson_progress lp
                        INNER JOIN lessons l ON lp.lesson_id = l.id
                        WHERE l.course_id = :cid AND lp.user_id = :uid AND lp.is_completed = 1";
        $completedResult = $this->fetchOne($completedSql, ['cid' => $courseId, 'uid' => $userId]);
        $completedLessons = $completedResult['total'];
        
        // Calculate progress
        $progress = ($completedLessons / $totalLessons) * 100;
        
        // Update enrollment
        $updateSql = "UPDATE {$this->table} 
                     SET progress = :progress" . ($progress >= 100 ? ", completed_at = NOW()" : "") . "
                     WHERE user_id = :uid AND course_id = :cid";
        
        return $this->query($updateSql, [
            'progress' => $progress,
            'uid' => $userId,
            'cid' => $courseId
        ]);
    }
    
    /**
     * L?y enrollment chi ti?t
     */
    public function getEnrollment($userId, $courseId) {
        return $this->findWhere('user_id = :uid AND course_id = :cid', [
            'uid' => $userId,
            'cid' => $courseId
        ]);
    }
    
    /**
     * Ki?m tra ?? ho?n th?nh kh?a h?c
     */
    public function isCompleted($userId, $courseId) {
        $enrollment = $this->getEnrollment($userId, $courseId);
        return $enrollment && $enrollment['completed_at'] !== null;
    }
    
    /**
     * L?y s? h?c vi?n c?a kh?a h?c
     */
    public function getStudentCount($courseId) {
        return $this->count('course_id = :cid', ['cid' => $courseId]);
    }
}
