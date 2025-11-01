&lt;?php
/**
 * File c?u h?nh h? th?ng E-Learning Platform
 * C?u h?nh cho XAMPP (PHP 8 + MySQL)
 */

// C?u h?nh Database
define('DB_HOST', 'localhost');
define('DB_NAME', 'elearning_db');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// C?u h?nh ???ng d?n
define('BASE_URL', 'http://localhost/elearning/');
define('ROOT_PATH', dirname(__DIR__) . '/');
define('PUBLIC_PATH', ROOT_PATH . 'public/');
define('UPLOAD_PATH', PUBLIC_PATH . 'uploads/');

// C?u h?nh session
define('SESSION_LIFETIME', 3600 * 24); // 24 gi?

// C?u h?nh ?ng d?ng
define('APP_NAME', 'Smart E-Learning Platform');
define('APP_VERSION', '1.0.0');
define('DEFAULT_LANG', 'vi');

// C?u h?nh b?o m?t
define('PASSWORD_HASH_ALGO', PASSWORD_BCRYPT);
define('PASSWORD_HASH_COST', 12);

// C?u h?nh upload
define('MAX_FILE_SIZE', 10 * 1024 * 1024); // 10MB
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/gif', 'image/webp']);
define('ALLOWED_DOC_TYPES', ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.presentationml.presentation']);

// C?u h?nh email (c? th? th?m sau)
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USER', 'your-email@gmail.com');
define('SMTP_PASS', 'your-password');

// C?u h?nh gamification
define('XP_PER_LESSON', 50);
define('XP_PER_QUIZ', 100);
define('XP_PER_COURSE_COMPLETE', 500);

// M?i gi?
date_default_timezone_set('Asia/Ho_Chi_Minh');

// B?t hi?n th? l?i (ch? d?ng khi development)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Kh?i ??ng session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
