<?php
/**
 * Note Model - Ghi ch?
 */

class Note extends Model {
    protected $table = 'notes';
    
    /**
     * L?y notes theo lesson
     */
    public function getLessonNotes($userId, $lessonId) {
        return $this->where(
            'user_id = :uid AND lesson_id = :lid',
            ['uid' => $userId, 'lid' => $lessonId],
            'created_at DESC'
        );
    }
    
    /**
     * L?y t?t c? notes c?a user
     */
    public function getUserNotes($userId, $courseId = null) {
        $sql = "SELECT n.*, l.title as lesson_title, c.title as course_title
                FROM {$this->table} n
                INNER JOIN lessons l ON n.lesson_id = l.id
                INNER JOIN courses c ON l.course_id = c.id
                WHERE n.user_id = :uid";
        
        $params = ['uid' => $userId];
        
        if ($courseId) {
            $sql .= " AND c.id = :cid";
            $params['cid'] = $courseId;
        }
        
        $sql .= " ORDER BY n.created_at DESC";
        
        return $this->fetchAll($sql, $params);
    }
    
    /**
     * T?o note m?i
     */
    public function createNote($userId, $lessonId, $content, $timestamp = null) {
        return $this->create([
            'user_id' => $userId,
            'lesson_id' => $lessonId,
            'content' => $content,
            'video_timestamp' => $timestamp
        ]);
    }
    
    /**
     * T?m ki?m notes
     */
    public function searchNotes($userId, $keyword) {
        $sql = "SELECT n.*, l.title as lesson_title, c.title as course_title
                FROM {$this->table} n
                INNER JOIN lessons l ON n.lesson_id = l.id
                INNER JOIN courses c ON l.course_id = c.id
                WHERE n.user_id = :uid
                AND n.content LIKE :keyword
                ORDER BY n.created_at DESC";
        
        return $this->fetchAll($sql, [
            'uid' => $userId,
            'keyword' => "%{$keyword}%"
        ]);
    }
}
