&lt;?php
/**
 * Forum Model
 */

class Forum extends Model {
    protected $table = 'forum_topics';
    
    /**
     * L?y t?t c? topics
     */
    public function getAllTopics($page = 1, $perPage = 20) {
        $offset = ($page - 1) * $perPage;
        
        $sql = "SELECT ft.*, u.name as author_name, u.avatar as author_avatar,
                c.title as course_title,
                (SELECT COUNT(*) FROM forum_replies WHERE topic_id = ft.id) as reply_count,
                (SELECT created_at FROM forum_replies WHERE topic_id = ft.id ORDER BY created_at DESC LIMIT 1) as last_reply
                FROM {$this->table} ft
                LEFT JOIN users u ON ft.user_id = u.id
                LEFT JOIN courses c ON ft.course_id = c.id
                ORDER BY ft.is_pinned DESC, ft.updated_at DESC
                LIMIT {$perPage} OFFSET {$offset}";
        
        return $this->fetchAll($sql);
    }
    
    /**
     * L?y topics theo kh?a h?c
     */
    public function getByCourse($courseId) {
        $sql = "SELECT ft.*, u.name as author_name, u.avatar as author_avatar,
                (SELECT COUNT(*) FROM forum_replies WHERE topic_id = ft.id) as reply_count
                FROM {$this->table} ft
                LEFT JOIN users u ON ft.user_id = u.id
                WHERE ft.course_id = :cid
                ORDER BY ft.is_pinned DESC, ft.updated_at DESC";
        
        return $this->fetchAll($sql, ['cid' => $courseId]);
    }
    
    /**
     * L?y topic v?i replies
     */
    public function getTopicWithReplies($topicId) {
        // Get topic
        $topicSql = "SELECT ft.*, u.name as author_name, u.avatar as author_avatar, u.role as author_role
                     FROM {$this->table} ft
                     LEFT JOIN users u ON ft.user_id = u.id
                     WHERE ft.id = :tid";
        $topic = $this->fetchOne($topicSql, ['tid' => $topicId]);
        
        if (!$topic) return null;
        
        // Increment view count
        $this->query("UPDATE {$this->table} SET view_count = view_count + 1 WHERE id = :tid", ['tid' => $topicId]);
        
        // Get replies
        $repliesSql = "SELECT fr.*, u.name as author_name, u.avatar as author_avatar, u.role as author_role
                      FROM forum_replies fr
                      LEFT JOIN users u ON fr.user_id = u.id
                      WHERE fr.topic_id = :tid
                      ORDER BY fr.created_at ASC";
        $replies = $this->fetchAll($repliesSql, ['tid' => $topicId]);
        
        $topic['replies'] = $replies;
        return $topic;
    }
    
    /**
     * T?o topic m?i
     */
    public function createTopic($data) {
        return $this->create($data);
    }
    
    /**
     * T?o reply
     */
    public function createReply($topicId, $userId, $content) {
        $result = $this->db->insert('forum_replies', [
            'topic_id' => $topicId,
            'user_id' => $userId,
            'content' => $content
        ]);
        
        if ($result) {
            // Update topic reply count
            $this->query("UPDATE {$this->table} SET reply_count = reply_count + 1, updated_at = NOW() WHERE id = :tid", 
                        ['tid' => $topicId]);
        }
        
        return $result;
    }
    
    /**
     * T?m ki?m topics
     */
    public function searchTopics($keyword) {
        $sql = "SELECT ft.*, u.name as author_name,
                (SELECT COUNT(*) FROM forum_replies WHERE topic_id = ft.id) as reply_count
                FROM {$this->table} ft
                LEFT JOIN users u ON ft.user_id = u.id
                WHERE ft.title LIKE :kw OR ft.content LIKE :kw
                ORDER BY ft.updated_at DESC
                LIMIT 50";
        
        return $this->fetchAll($sql, ['kw' => "%{$keyword}%"]);
    }
    
    /**
     * Ghim topic
     */
    public function pinTopic($topicId, $pin = true) {
        return $this->update($topicId, ['is_pinned' => $pin]);
    }
    
    /**
     * Kh?a topic
     */
    public function lockTopic($topicId, $lock = true) {
        return $this->update($topicId, ['is_locked' => $lock]);
    }
    
    /**
     * ??nh d?u reply l? solution
     */
    public function markAsSolution($replyId) {
        return $this->db->update('forum_replies', ['is_solution' => 1], 'id = :id', ['id' => $replyId]);
    }
    
    /**
     * L?y topics hot nh?t
     */
    public function getHotTopics($limit = 5) {
        $sql = "SELECT ft.*, u.name as author_name,
                (SELECT COUNT(*) FROM forum_replies WHERE topic_id = ft.id) as reply_count
                FROM {$this->table} ft
                LEFT JOIN users u ON ft.user_id = u.id
                WHERE ft.created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
                ORDER BY ft.view_count DESC, reply_count DESC
                LIMIT {$limit}";
        
        return $this->fetchAll($sql);
    }
}
