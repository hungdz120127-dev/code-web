&lt;?php
/**
 * Badge Model
 */

class Badge extends Model {
    protected $table = 'badges';
    
    /**
     * L?y t?t c? badges
     */
    public function getAllBadges() {
        return $this->all('xp_required ASC');
    }
    
    /**
     * L?y badges c?a user
     */
    public function getUserBadges($userId) {
        $sql = "SELECT b.*, ub.earned_at
                FROM {$this->table} b
                INNER JOIN user_badges ub ON b.id = ub.badge_id
                WHERE ub.user_id = :uid
                ORDER BY ub.earned_at DESC";
        
        return $this->fetchAll($sql, ['uid' => $userId]);
    }
    
    /**
     * Ki?m tra user ?? c? badge ch?a
     */
    public function hasBadge($userId, $badgeId) {
        $sql = "SELECT COUNT(*) as total FROM user_badges 
                WHERE user_id = :uid AND badge_id = :bid";
        $result = $this->fetchOne($sql, ['uid' => $userId, 'bid' => $badgeId]);
        return $result['total'] > 0;
    }
    
    /**
     * Trao badge cho user
     */
    public function awardBadge($userId, $badgeId) {
        if ($this->hasBadge($userId, $badgeId)) {
            return false;
        }
        
        $result = $this->db->insert('user_badges', [
            'user_id' => $userId,
            'badge_id' => $badgeId
        ]);
        
        if ($result) {
            // Create notification
            require_once ROOT_PATH . 'models/Notification.php';
            $notificationModel = new Notification();
            
            $badge = $this->find($badgeId);
            $notificationModel->createNotification(
                $userId,
                'Ch?c m?ng! B?n nh?n ???c huy hi?u m?i',
                'B?n v?a nh?n ???c huy hi?u: ' . $badge['name'],
                'success',
                'profile'
            );
        }
        
        return $result;
    }
    
    /**
     * Ki?m tra v? trao badges t? ??ng
     */
    public function checkAndAwardBadges($userId) {
        require_once ROOT_PATH . 'models/User.php';
        require_once ROOT_PATH . 'models/Enrollment.php';
        
        $userModel = new User();
        $enrollmentModel = new Enrollment();
        
        $user = $userModel->find($userId);
        $completedCourses = $enrollmentModel->count('user_id = :uid AND completed_at IS NOT NULL', ['uid' => $userId]);
        
        // Badge logic
        $badgesToCheck = [
            1 => $completedCourses >= 1,  // Ng??i m?i b?t ??u
            2 => $completedCourses >= 5,  // H?c vi?n ch?m ch?
            3 => $completedCourses >= 10, // Chuy?n gia
            4 => $user['xp'] >= 5000,     // B?c th?y
        ];
        
        foreach ($badgesToCheck as $badgeId => $condition) {
            if ($condition && !$this->hasBadge($userId, $badgeId)) {
                $this->awardBadge($userId, $badgeId);
            }
        }
    }
}
