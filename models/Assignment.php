<?php
/**
 * Assignment Model - B?i t?p
 */

class Assignment extends Model {
    protected $table = 'assignments';
    
    /**
     * L?y assignments theo kh?a h?c
     */
    public function getByCourse($courseId) {
        $sql = "SELECT a.*, 
                (SELECT COUNT(*) FROM assignment_submissions WHERE assignment_id = a.id) as submission_count
                FROM {$this->table} a
                WHERE a.course_id = :cid
                ORDER BY a.due_date DESC";
        
        return $this->fetchAll($sql, ['cid' => $courseId]);
    }
    
    /**
     * L?y assignments c?a student
     */
    public function getStudentAssignments($userId) {
        $sql = "SELECT a.*, c.title as course_title,
                asub.id as submission_id,
                asub.submitted_at,
                asub.grade,
                asub.feedback,
                asub.status,
                CASE 
                    WHEN asub.id IS NULL THEN 'pending'
                    WHEN asub.status = 'graded' THEN 'graded'
                    ELSE 'submitted'
                END as student_status
                FROM {$this->table} a
                INNER JOIN courses c ON a.course_id = c.id
                INNER JOIN enrollments e ON c.id = e.course_id
                LEFT JOIN assignment_submissions asub ON a.id = asub.assignment_id AND asub.user_id = :uid
                WHERE e.user_id = :uid
                ORDER BY a.due_date ASC";
        
        return $this->fetchAll($sql, ['uid' => $userId]);
    }
    
    /**
     * Submit assignment
     */
    public function submitAssignment($userId, $assignmentId, $data) {
        // Check if already submitted
        $existing = $this->db->fetchOne(
            "SELECT * FROM assignment_submissions WHERE user_id = :uid AND assignment_id = :aid",
            ['uid' => $userId, 'aid' => $assignmentId]
        );
        
        if ($existing) {
            return ['success' => false, 'message' => 'B?n ?? n?p b?i t?p n?y r?i'];
        }
        
        $submissionData = [
            'user_id' => $userId,
            'assignment_id' => $assignmentId,
            'content' => $data['content'],
            'file_path' => $data['file_path'] ?? null,
            'status' => 'submitted'
        ];
        
        $result = $this->db->insert('assignment_submissions', $submissionData);
        
        if ($result) {
            // Create notification for teacher
            $assignment = $this->find($assignmentId);
            $course = $this->db->fetchOne("SELECT teacher_id FROM courses WHERE id = :cid", ['cid' => $assignment['course_id']]);
            
            require_once ROOT_PATH . 'models/Notification.php';
            $notificationModel = new Notification();
            $notificationModel->createNotification(
                $course['teacher_id'],
                'B?i t?p m?i ???c n?p',
                'C? h?c sinh v?a n?p b?i t?p: ' . $assignment['title'],
                'info',
                'assignment/grade/' . $result
            );
            
            return ['success' => true, 'message' => 'N?p b?i th?nh c?ng'];
        }
        
        return ['success' => false, 'message' => 'C? l?i x?y ra'];
    }
    
    /**
     * Grade assignment
     */
    public function gradeAssignment($submissionId, $grade, $feedback) {
        $result = $this->db->update(
            'assignment_submissions',
            [
                'grade' => $grade,
                'feedback' => $feedback,
                'status' => 'graded',
                'graded_at' => date('Y-m-d H:i:s')
            ],
            'id = :id',
            ['id' => $submissionId]
        );
        
        if ($result) {
            // Notify student
            $submission = $this->db->fetchOne("SELECT * FROM assignment_submissions WHERE id = :id", ['id' => $submissionId]);
            
            require_once ROOT_PATH . 'models/Notification.php';
            $notificationModel = new Notification();
            $notificationModel->createNotification(
                $submission['user_id'],
                'B?i t?p ?? ???c ch?m',
                'B?i t?p c?a b?n ?? ???c ch?m ?i?m: ' . $grade,
                'success',
                'assignment/view/' . $submission['assignment_id']
            );
        }
        
        return $result;
    }
    
    /**
     * Get submissions for assignment
     */
    public function getSubmissions($assignmentId) {
        $sql = "SELECT asub.*, u.name as student_name, u.email as student_email, u.avatar
                FROM assignment_submissions asub
                INNER JOIN users u ON asub.user_id = u.id
                WHERE asub.assignment_id = :aid
                ORDER BY asub.submitted_at DESC";
        
        return $this->fetchAll($sql, ['aid' => $assignmentId]);
    }
}
