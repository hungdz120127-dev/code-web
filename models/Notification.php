&lt;?php
/**
 * Notification Model
 */

class Notification extends Model {
    protected $table = 'notifications';
    
    /**
     * T?o th?ng b?o
     */
    public function createNotification($userId, $title, $message, $type = 'info', $link = null) {
        return $this->create([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'link' => $link
        ]);
    }
    
    /**
     * L?y th?ng b?o c?a user
     */
    public function getUserNotifications($userId, $limit = 20) {
        return $this->where(
            'user_id = :uid',
            ['uid' => $userId],
            'created_at DESC',
            $limit
        );
    }
    
    /**
     * L?y th?ng b?o ch?a ??c
     */
    public function getUnreadNotifications($userId) {
        return $this->where(
            'user_id = :uid AND is_read = 0',
            ['uid' => $userId],
            'created_at DESC'
        );
    }
    
    /**
     * ??m th?ng b?o ch?a ??c
     */
    public function countUnread($userId) {
        return $this->count('user_id = :uid AND is_read = 0', ['uid' => $userId]);
    }
    
    /**
     * ??nh d?u ?? ??c
     */
    public function markAsRead($notificationId) {
        return $this->update($notificationId, ['is_read' => 1]);
    }
    
    /**
     * ??nh d?u t?t c? ?? ??c
     */
    public function markAllAsRead($userId) {
        $sql = "UPDATE {$this->table} SET is_read = 1 WHERE user_id = :uid AND is_read = 0";
        return $this->query($sql, ['uid' => $userId]);
    }
    
    /**
     * X?a th?ng b?o c?
     */
    public function deleteOld($days = 30) {
        $sql = "DELETE FROM {$this->table} WHERE created_at < DATE_SUB(NOW(), INTERVAL :days DAY)";
        return $this->query($sql, ['days' => $days]);
    }
}
