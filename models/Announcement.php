<?php
/**
 * Announcement Model - Th?ng b?o kh?a h?c
 */

class Announcement extends Model {
    protected $table = 'announcements';
    
    /**
     * L?y announcements theo kh?a h?c
     */
    public function getByCourse($courseId) {
        $sql = "SELECT a.*, u.name as author_name, u.avatar as author_avatar
                FROM {$this->table} a
                INNER JOIN users u ON a.created_by = u.id
                WHERE a.course_id = :cid
                ORDER BY a.is_pinned DESC, a.created_at DESC";
        
        return $this->fetchAll($sql, ['cid' => $courseId]);
    }
    
    /**
     * T?o announcement m?i
     */
    public function createAnnouncement($data) {
        $announcementId = $this->create($data);
        
        if ($announcementId) {
            // Notify all enrolled students
            $students = $this->db->fetchAll(
                "SELECT user_id FROM enrollments WHERE course_id = :cid",
                ['cid' => $data['course_id']]
            );
            
            require_once ROOT_PATH . 'models/Notification.php';
            $notificationModel = new Notification();
            
            foreach ($students as $student) {
                $notificationModel->createNotification(
                    $student['user_id'],
                    'Th?ng b?o m?i: ' . $data['title'],
                    substr($data['content'], 0, 100) . '...',
                    'info',
                    'course/announcements/' . $data['course_id']
                );
            }
        }
        
        return $announcementId;
    }
    
    /**
     * Pin announcement
     */
    public function pinAnnouncement($announcementId, $pin = true) {
        return $this->update($announcementId, ['is_pinned' => $pin]);
    }
    
    /**
     * L?y recent announcements
     */
    public function getRecentAnnouncements($userId, $limit = 5) {
        $sql = "SELECT a.*, c.title as course_title, u.name as author_name
                FROM {$this->table} a
                INNER JOIN courses c ON a.course_id = c.id
                INNER JOIN enrollments e ON c.id = e.course_id
                INNER JOIN users u ON a.created_by = u.id
                WHERE e.user_id = :uid
                ORDER BY a.created_at DESC
                LIMIT {$limit}";
        
        return $this->fetchAll($sql, ['uid' => $userId]);
    }
}
