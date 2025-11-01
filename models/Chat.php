&lt;?php
/**
 * Chat Model
 */

class Chat extends Model {
    protected $table = 'chat_messages';
    
    /**
     * G?i tin nh?n
     */
    public function sendMessage($senderId, $receiverId, $message) {
        return $this->create([
            'sender_id' => $senderId,
            'receiver_id' => $receiverId,
            'message' => $message
        ]);
    }
    
    /**
     * L?y tin nh?n gi?a 2 users
     */
    public function getMessages($userId1, $userId2, $limit = 50) {
        $sql = "SELECT cm.*, 
                u1.name as sender_name, u1.avatar as sender_avatar,
                u2.name as receiver_name, u2.avatar as receiver_avatar
                FROM {$this->table} cm
                LEFT JOIN users u1 ON cm.sender_id = u1.id
                LEFT JOIN users u2 ON cm.receiver_id = u2.id
                WHERE (cm.sender_id = :uid1 AND cm.receiver_id = :uid2)
                   OR (cm.sender_id = :uid2 AND cm.receiver_id = :uid1)
                ORDER BY cm.created_at ASC
                LIMIT {$limit}";
        
        return $this->fetchAll($sql, ['uid1' => $userId1, 'uid2' => $userId2]);
    }
    
    /**
     * L?y danh s?ch conversations
     */
    public function getConversations($userId) {
        $sql = "SELECT DISTINCT 
                CASE 
                    WHEN cm.sender_id = :uid THEN cm.receiver_id
                    ELSE cm.sender_id
                END as contact_id,
                u.name as contact_name,
                u.avatar as contact_avatar,
                u.role as contact_role,
                (SELECT message FROM {$this->table} 
                 WHERE (sender_id = :uid AND receiver_id = contact_id)
                    OR (receiver_id = :uid AND sender_id = contact_id)
                 ORDER BY created_at DESC LIMIT 1) as last_message,
                (SELECT created_at FROM {$this->table} 
                 WHERE (sender_id = :uid AND receiver_id = contact_id)
                    OR (receiver_id = :uid AND sender_id = contact_id)
                 ORDER BY created_at DESC LIMIT 1) as last_message_time,
                (SELECT COUNT(*) FROM {$this->table} 
                 WHERE sender_id = contact_id AND receiver_id = :uid AND is_read = 0) as unread_count
                FROM {$this->table} cm
                LEFT JOIN users u ON u.id = CASE 
                    WHEN cm.sender_id = :uid THEN cm.receiver_id
                    ELSE cm.sender_id
                END
                WHERE cm.sender_id = :uid OR cm.receiver_id = :uid
                ORDER BY last_message_time DESC";
        
        return $this->fetchAll($sql, ['uid' => $userId]);
    }
    
    /**
     * ??nh d?u tin nh?n ?? ??c
     */
    public function markAsRead($senderId, $receiverId) {
        $sql = "UPDATE {$this->table} 
                SET is_read = 1 
                WHERE sender_id = :sid AND receiver_id = :rid AND is_read = 0";
        
        return $this->query($sql, ['sid' => $senderId, 'rid' => $receiverId]);
    }
    
    /**
     * ??m tin nh?n ch?a ??c
     */
    public function countUnread($userId) {
        return $this->count('receiver_id = :uid AND is_read = 0', ['uid' => $userId]);
    }
    
    /**
     * L?y tin nh?n m?i (AJAX polling)
     */
    public function getNewMessages($userId, $contactId, $lastMessageId = 0) {
        $sql = "SELECT cm.*, 
                u.name as sender_name, u.avatar as sender_avatar
                FROM {$this->table} cm
                LEFT JOIN users u ON cm.sender_id = u.id
                WHERE cm.id > :lastId
                AND ((cm.sender_id = :uid AND cm.receiver_id = :cid)
                  OR (cm.sender_id = :cid AND cm.receiver_id = :uid))
                ORDER BY cm.created_at ASC";
        
        return $this->fetchAll($sql, [
            'lastId' => $lastMessageId,
            'uid' => $userId,
            'cid' => $contactId
        ]);
    }
}
