&lt;?php
/**
 * Admin Controller
 */

class AdminController extends Controller {
    
    public function index() {
        $this->requireRole('admin');
        
        $user = $this->getCurrentUser();
        
        // Get statistics
        $userModel = $this->model('User');
        $courseModel = $this->model('Course');
        $enrollmentModel = $this->model('Enrollment');
        
        $stats = [
            'total_users' => $userModel->count(),
            'total_students' => $userModel->count('role = :role', ['role' => 'student']),
            'total_teachers' => $userModel->count('role = :role', ['role' => 'teacher']),
            'total_courses' => $courseModel->count(),
            'total_enrollments' => $enrollmentModel->count()
        ];
        
        // Get recent users
        $recentUsers = $userModel->where('1=1', [], 'created_at DESC', 10);
        
        // Get recent courses
        $recentCourses = $courseModel->all('created_at DESC', 10);
        
        $data = [
            'title' => 'Admin Dashboard',
            'stats' => $stats,
            'recentUsers' => $recentUsers,
            'recentCourses' => $recentCourses,
            'user' => $user
        ];
        
        $this->view('admin/index', $data);
    }
    
    public function users() {
        $this->requireRole('admin');
        
        $userModel = $this->model('User');
        
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = 20;
        
        $users = $userModel->paginate($page, $perPage, '1=1', [], 'created_at DESC');
        
        $data = [
            'title' => 'Qu?n l? ng??i d?ng',
            'users' => $users,
            'user' => $this->getCurrentUser()
        ];
        
        $this->view('admin/users', $data);
    }
    
    public function courses() {
        $this->requireRole('admin');
        
        $courseModel = $this->model('Course');
        
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = 20;
        
        $courses = $courseModel->paginate($page, $perPage, '1=1', [], 'created_at DESC');
        
        $data = [
            'title' => 'Qu?n l? kh?a h?c',
            'courses' => $courses,
            'user' => $this->getCurrentUser()
        ];
        
        $this->view('admin/courses', $data);
    }
    
    public function backup() {
        $this->requireRole('admin');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->createBackup();
        }
        
        $data = [
            'title' => 'Backup & Restore',
            'user' => $this->getCurrentUser()
        ];
        
        $this->view('admin/backup', $data);
    }
    
    private function createBackup() {
        $backupDir = ROOT_PATH . 'backups/';
        if (!file_exists($backupDir)) {
            mkdir($backupDir, 0755, true);
        }
        
        $filename = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
        $filepath = $backupDir . $filename;
        
        $command = sprintf(
            'mysqldump --user=%s --password=%s --host=%s %s > %s',
            DB_USER,
            DB_PASS,
            DB_HOST,
            DB_NAME,
            escapeshellarg($filepath)
        );
        
        exec($command, $output, $result);
        
        if ($result === 0) {
            $this->json([
                'success' => true,
                'message' => 'Backup th?nh c?ng',
                'filename' => $filename
            ]);
        } else {
            $this->json(['success' => false, 'message' => 'Backup th?t b?i'], 500);
        }
    }
    
    public function settings() {
        $this->requireRole('admin');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->updateSettings();
        }
        
        // Get current settings
        $sql = "SELECT * FROM settings";
        $settings = $this->db->fetchAll($sql);
        
        $settingsArray = [];
        foreach ($settings as $setting) {
            $settingsArray[$setting['setting_key']] = $setting['setting_value'];
        }
        
        $data = [
            'title' => 'C?i ??t h? th?ng',
            'settings' => $settingsArray,
            'user' => $this->getCurrentUser()
        ];
        
        $this->view('admin/settings', $data);
    }
    
    private function updateSettings() {
        foreach ($_POST as $key => $value) {
            $sql = "INSERT INTO settings (setting_key, setting_value) 
                    VALUES (:key, :value)
                    ON DUPLICATE KEY UPDATE setting_value = :value";
            $this->db->query($sql, ['key' => $key, 'value' => $value]);
        }
        
        $this->json(['success' => true, 'message' => 'C?p nh?t c?i ??t th?nh c?ng']);
    }
}
