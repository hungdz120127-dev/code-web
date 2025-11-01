<?php
/**
 * File cau hinh he thong E-Learning Platform
 * Cau hinh cho XAMPP (PHP 8 + MySQL)
 */

// Cau hinh Database
define('DB_HOST', 'localhost');
define('DB_NAME', 'elearning_db');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Cau hinh duong dan
define('BASE_URL', 'http://localhost/elearning/');
define('ROOT_PATH', dirname(__DIR__) . '/');
define('PUBLIC_PATH', ROOT_PATH . 'public/');
define('UPLOAD_PATH', PUBLIC_PATH . 'uploads/');

// Cau hinh session
define('SESSION_LIFETIME', 3600 * 24); // 24 gio

// Cau hinh ung dung
define('APP_NAME', 'Smart E-Learning Platform');
define('APP_VERSION', '1.0.0');
define('DEFAULT_LANG', 'vi');

// Cau hinh bao mat
define('PASSWORD_HASH_ALGO', PASSWORD_BCRYPT);
define('PASSWORD_HASH_COST', 12);

// Cau hinh upload
define('MAX_FILE_SIZE', 10 * 1024 * 1024); // 10MB
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/gif', 'image/webp']);
define('ALLOWED_DOC_TYPES', [
    'application/pdf',
    'application/msword',
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    'application/vnd.ms-powerpoint',
    'application/vnd.openxmlformats-officedocument.presentationml.presentation'
]);

// Cau hinh email (co the them sau)
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USER', 'your-email@gmail.com');
define('SMTP_PASS', 'your-password');

// Cau hinh gamification
define('XP_PER_LESSON', 50);
define('XP_PER_QUIZ', 100);
define('XP_PER_COURSE_COMPLETE', 500);

// Mui gio
date_default_timezone_set('Asia/Ho_Chi_Minh');

// Bat hien thi loi (chi dung khi development)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Khoi dong session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
