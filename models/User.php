&lt;?php
/**
 * User Model
 */

class User extends Model {
    protected $table = 'users';
    
    /**
     * T?m user theo email
     */
    public function findByEmail($email) {
        return $this->findWhere('email = :email', ['email' => $email]);
    }
    
    /**
     * T?o user m?i
     */
    public function register($data) {
        // Hash password
        $data['password'] = password_hash($data['password'], PASSWORD_HASH_ALGO, ['cost' => PASSWORD_HASH_COST]);
        
        // Set default values
        $data['xp'] = 0;
        $data['level'] = 1;
        $data['status'] = 'active';
        
        return $this->create($data);
    }
    
    /**
     * ??ng nh?p
     */
    public function login($email, $password) {
        $user = $this->findByEmail($email);
        
        if ($user && password_verify($password, $user['password'])) {
            if ($user['status'] !== 'active') {
                return ['success' => false, 'message' => 'T?i kho?n ?? b? kh?a'];
            }
            
            // Update last login
            $this->update($user['id'], ['last_login' => date('Y-m-d H:i:s')]);
            
            // Set session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['user_avatar'] = $user['avatar'];
            
            return ['success' => true, 'user' => $user];
        }
        
        return ['success' => false, 'message' => 'Email ho?c m?t kh?u kh?ng ??ng'];
    }
    
    /**
     * ??ng xu?t
     */
    public function logout() {
        session_destroy();
    }
    
    /**
     * Th?m XP cho user
     */
    public function addXP($userId, $xp) {
        $user = $this->find($userId);
        if (!$user) return false;
        
        $newXP = $user['xp'] + $xp;
        $newLevel = $this->calculateLevel($newXP);
        
        return $this->update($userId, [
            'xp' => $newXP,
            'level' => $newLevel
        ]);
    }
    
    /**
     * T?nh level d?a tr?n XP
     */
    private function calculateLevel($xp) {
        // C?ng th?c: Level = floor(sqrt(XP / 100)) + 1
        return floor(sqrt($xp / 100)) + 1;
    }
    
    /**
     * L?y top users theo XP
     */
    public function getLeaderboard($limit = 10) {
        $sql = "SELECT id, name, avatar, xp, level, role 
                FROM {$this->table} 
                WHERE role = 'student' 
                ORDER BY xp DESC 
                LIMIT {$limit}";
        return $this->fetchAll($sql);
    }
    
    /**
     * L?y th?ng k? user
     */
    public function getStats($userId) {
        $sql = "SELECT 
                    (SELECT COUNT(*) FROM enrollments WHERE user_id = :uid) as enrolled_courses,
                    (SELECT COUNT(*) FROM enrollments WHERE user_id = :uid AND completed_at IS NOT NULL) as completed_courses,
                    (SELECT COUNT(*) FROM quiz_results WHERE user_id = :uid) as total_quizzes,
                    (SELECT COUNT(*) FROM certificates WHERE user_id = :uid) as total_certificates,
                    (SELECT COUNT(*) FROM user_badges WHERE user_id = :uid) as total_badges
                FROM users WHERE id = :uid LIMIT 1";
        
        return $this->fetchOne($sql, ['uid' => $userId]);
    }
    
    /**
     * L?y danh s?ch gi?o vi?n
     */
    public function getTeachers() {
        return $this->where('role = :role', ['role' => 'teacher'], 'name ASC');
    }
    
    /**
     * C?p nh?t avatar
     */
    public function updateAvatar($userId, $avatar) {
        return $this->update($userId, ['avatar' => $avatar]);
    }
    
    /**
     * ??i m?t kh?u
     */
    public function changePassword($userId, $oldPassword, $newPassword) {
        $user = $this->find($userId);
        
        if (!password_verify($oldPassword, $user['password'])) {
            return ['success' => false, 'message' => 'M?t kh?u c? kh?ng ??ng'];
        }
        
        $hashedPassword = password_hash($newPassword, PASSWORD_HASH_ALGO, ['cost' => PASSWORD_HASH_COST]);
        $result = $this->update($userId, ['password' => $hashedPassword]);
        
        return ['success' => $result, 'message' => $result ? '??i m?t kh?u th?nh c?ng' : 'C? l?i x?y ra'];
    }
}
