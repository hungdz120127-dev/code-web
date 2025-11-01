&lt;?php
/**
 * Auth Controller
 */

class AuthController extends Controller {
    
    public function login() {
        // N?u ?? ??ng nh?p, redirect v? dashboard
        if ($this->isLoggedIn()) {
            $this->redirect('dashboard');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $this->sanitize($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            
            $userModel = $this->model('User');
            $result = $userModel->login($email, $password);
            
            if ($result['success']) {
                // Redirect after login
                $redirect = $_SESSION['redirect_after_login'] ?? 'dashboard';
                unset($_SESSION['redirect_after_login']);
                $this->redirect($redirect);
            } else {
                $this->setFlash('error', $result['message']);
            }
        }
        
        $data = [
            'title' => '??ng nh?p - ' . APP_NAME
        ];
        
        $this->view('auth/login', $data);
    }
    
    public function register() {
        if ($this->isLoggedIn()) {
            $this->redirect('dashboard');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $this->sanitize($_POST['name'] ?? '');
            $email = $this->sanitize($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            $role = $this->sanitize($_POST['role'] ?? 'student');
            
            // Validation
            $errors = [];
            
            if (empty($name)) {
                $errors[] = 'Vui l?ng nh?p h? t?n';
            }
            
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Email kh?ng h?p l?';
            }
            
            if (strlen($password) < 6) {
                $errors[] = 'M?t kh?u ph?i c? ?t nh?t 6 k? t?';
            }
            
            if ($password !== $confirmPassword) {
                $errors[] = 'M?t kh?u x?c nh?n kh?ng kh?p';
            }
            
            // Check email exists
            $userModel = $this->model('User');
            if ($userModel->findByEmail($email)) {
                $errors[] = 'Email ?? ???c s? d?ng';
            }
            
            if (empty($errors)) {
                $userId = $userModel->register([
                    'name' => $name,
                    'email' => $email,
                    'password' => $password,
                    'role' => in_array($role, ['student', 'teacher']) ? $role : 'student'
                ]);
                
                if ($userId) {
                    $this->setFlash('success', '??ng k? th?nh c?ng! Vui l?ng ??ng nh?p.');
                    $this->redirect('auth/login');
                } else {
                    $this->setFlash('error', 'C? l?i x?y ra. Vui l?ng th? l?i.');
                }
            } else {
                $this->setFlash('error', implode('<br>', $errors));
            }
        }
        
        $data = [
            'title' => '??ng k? - ' . APP_NAME
        ];
        
        $this->view('auth/register', $data);
    }
    
    public function logout() {
        $userModel = $this->model('User');
        $userModel->logout();
        $this->redirect('');
    }
    
    public function profile() {
        $this->requireLogin();
        
        $user = $this->getCurrentUser();
        $userModel = $this->model('User');
        
        // Get user stats
        $stats = $userModel->getStats($user['id']);
        
        // Get badges
        $badgeModel = $this->model('Badge');
        $badges = $badgeModel->getUserBadges($user['id']);
        
        // Get certificates
        $certificateModel = $this->model('Certificate');
        $certificates = $certificateModel->getUserCertificates($user['id']);
        
        $data = [
            'title' => 'H? s? c? nh?n - ' . APP_NAME,
            'user' => $user,
            'stats' => $stats,
            'badges' => $badges,
            'certificates' => $certificates
        ];
        
        $this->view('auth/profile', $data);
    }
    
    public function updateProfile() {
        $this->requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = $this->getCurrentUser();
            $userModel = $this->model('User');
            
            $name = $this->sanitize($_POST['name'] ?? '');
            $phone = $this->sanitize($_POST['phone'] ?? '');
            $bio = $this->sanitize($_POST['bio'] ?? '');
            
            $updateData = [
                'name' => $name,
                'phone' => $phone,
                'bio' => $bio
            ];
            
            // Handle avatar upload
            if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === 0) {
                $result = $this->uploadFile(
                    $_FILES['avatar'],
                    UPLOAD_PATH . 'avatars/',
                    ALLOWED_IMAGE_TYPES
                );
                
                if ($result['success']) {
                    $updateData['avatar'] = $result['filename'];
                }
            }
            
            if ($userModel->update($user['id'], $updateData)) {
                $_SESSION['user_name'] = $name;
                if (isset($updateData['avatar'])) {
                    $_SESSION['user_avatar'] = $updateData['avatar'];
                }
                
                $this->json(['success' => true, 'message' => 'C?p nh?t th?nh c?ng']);
            } else {
                $this->json(['success' => false, 'message' => 'C? l?i x?y ra'], 400);
            }
        }
    }
    
    public function changePassword() {
        $this->requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = $this->getCurrentUser();
            $userModel = $this->model('User');
            
            $oldPassword = $_POST['old_password'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            
            if ($newPassword !== $confirmPassword) {
                $this->json(['success' => false, 'message' => 'M?t kh?u x?c nh?n kh?ng kh?p'], 400);
            }
            
            if (strlen($newPassword) < 6) {
                $this->json(['success' => false, 'message' => 'M?t kh?u ph?i c? ?t nh?t 6 k? t?'], 400);
            }
            
            $result = $userModel->changePassword($user['id'], $oldPassword, $newPassword);
            
            if ($result['success']) {
                $this->json(['success' => true, 'message' => $result['message']]);
            } else {
                $this->json(['success' => false, 'message' => $result['message']], 400);
            }
        }
    }
}
