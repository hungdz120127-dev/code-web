&lt;?php
/**
 * Base Controller Class
 * Class cha cho t?t c? controllers
 */

class Controller {
    protected $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Load view
     */
    protected function view($view, $data = []) {
        extract($data);
        $viewPath = ROOT_PATH . 'views/' . $view . '.php';
        
        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            die("View not found: {$view}");
        }
    }
    
    /**
     * Load model
     */
    protected function model($model) {
        $modelPath = ROOT_PATH . 'models/' . $model . '.php';
        
        if (file_exists($modelPath)) {
            require_once $modelPath;
            return new $model();
        } else {
            die("Model not found: {$model}");
        }
    }
    
    /**
     * Redirect
     */
    protected function redirect($url) {
        header("Location: " . BASE_URL . $url);
        exit;
    }
    
    /**
     * JSON response
     */
    protected function json($data, $status = 200) {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    /**
     * Check if user is logged in
     */
    protected function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
    
    /**
     * Get current user
     */
    protected function getCurrentUser() {
        if (!$this->isLoggedIn()) {
            return null;
        }
        
        $userModel = $this->model('User');
        return $userModel->find($_SESSION['user_id']);
    }
    
    /**
     * Require login
     */
    protected function requireLogin() {
        if (!$this->isLoggedIn()) {
            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
            $this->redirect('auth/login');
        }
    }
    
    /**
     * Require role
     */
    protected function requireRole($roles) {
        $this->requireLogin();
        
        $user = $this->getCurrentUser();
        if (!in_array($user['role'], (array)$roles)) {
            $_SESSION['error'] = 'B?n kh?ng c? quy?n truy c?p trang n?y.';
            $this->redirect('dashboard');
        }
    }
    
    /**
     * Set flash message
     */
    protected function setFlash($type, $message) {
        $_SESSION['flash_' . $type] = $message;
    }
    
    /**
     * Get and clear flash message
     */
    protected function getFlash($type) {
        if (isset($_SESSION['flash_' . $type])) {
            $message = $_SESSION['flash_' . $type];
            unset($_SESSION['flash_' . $type]);
            return $message;
        }
        return null;
    }
    
    /**
     * Validate CSRF token
     */
    protected function validateCSRF($token) {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
    
    /**
     * Generate CSRF token
     */
    protected function generateCSRF() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
    
    /**
     * Sanitize input
     */
    protected function sanitize($input) {
        if (is_array($input)) {
            return array_map([$this, 'sanitize'], $input);
        }
        return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Upload file
     */
    protected function uploadFile($file, $destination, $allowedTypes = []) {
        if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            return ['success' => false, 'message' => 'No file uploaded'];
        }
        
        if ($file['size'] > MAX_FILE_SIZE) {
            return ['success' => false, 'message' => 'File qu? l?n'];
        }
        
        if (!empty($allowedTypes) && !in_array($file['type'], $allowedTypes)) {
            return ['success' => false, 'message' => 'Lo?i file kh?ng ???c ph?p'];
        }
        
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '_' . time() . '.' . $extension;
        $uploadPath = $destination . $filename;
        
        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            return ['success' => true, 'filename' => $filename];
        }
        
        return ['success' => false, 'message' => 'Upload failed'];
    }
}
