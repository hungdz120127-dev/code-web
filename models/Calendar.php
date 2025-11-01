<?php
/**
 * Calendar Model - L?ch h?c
 */

class Calendar extends Model {
    protected $table = 'calendar_events';
    
    /**
     * L?y events theo user
     */
    public function getUserEvents($userId, $startDate = null, $endDate = null) {
        $sql = "SELECT ce.*, c.title as course_title, c.thumbnail
                FROM {$this->table} ce
                INNER JOIN courses c ON ce.course_id = c.id
                INNER JOIN enrollments e ON c.id = e.course_id
                WHERE e.user_id = :uid";
        
        $params = ['uid' => $userId];
        
        if ($startDate) {
            $sql .= " AND ce.start_date >= :start";
            $params['start'] = $startDate;
        }
        
        if ($endDate) {
            $sql .= " AND ce.end_date <= :end";
            $params['end'] = $endDate;
        }
        
        $sql .= " ORDER BY ce.start_date ASC";
        
        return $this->fetchAll($sql, $params);
    }
    
    /**
     * L?y events theo teacher
     */
    public function getTeacherEvents($teacherId, $startDate = null, $endDate = null) {
        $sql = "SELECT ce.*, c.title as course_title
                FROM {$this->table} ce
                INNER JOIN courses c ON ce.course_id = c.id
                WHERE c.teacher_id = :tid";
        
        $params = ['tid' => $teacherId];
        
        if ($startDate) {
            $sql .= " AND ce.start_date >= :start";
            $params['start'] = $startDate;
        }
        
        if ($endDate) {
            $sql .= " AND ce.end_date <= :end";
            $params['end'] = $endDate;
        }
        
        $sql .= " ORDER BY ce.start_date ASC";
        
        return $this->fetchAll($sql, $params);
    }
    
    /**
     * T?o event m?i
     */
    public function createEvent($data) {
        $eventId = $this->create($data);
        
        if ($eventId) {
            // Notify enrolled students
            $students = $this->db->fetchAll(
                "SELECT user_id FROM enrollments WHERE course_id = :cid",
                ['cid' => $data['course_id']]
            );
            
            require_once ROOT_PATH . 'models/Notification.php';
            $notificationModel = new Notification();
            
            foreach ($students as $student) {
                $notificationModel->createNotification(
                    $student['user_id'],
                    'S? ki?n m?i: ' . $data['title'],
                    $data['description'],
                    'info',
                    'calendar'
                );
            }
        }
        
        return $eventId;
    }
    
    /**
     * L?y upcoming events
     */
    public function getUpcomingEvents($userId, $limit = 5) {
        $sql = "SELECT ce.*, c.title as course_title, c.thumbnail
                FROM {$this->table} ce
                INNER JOIN courses c ON ce.course_id = c.id
                INNER JOIN enrollments e ON c.id = e.course_id
                WHERE e.user_id = :uid
                AND ce.start_date >= NOW()
                ORDER BY ce.start_date ASC
                LIMIT {$limit}";
        
        return $this->fetchAll($sql, ['uid' => $userId]);
    }
}
