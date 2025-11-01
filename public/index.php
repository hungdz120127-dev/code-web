<?php
/**
 * Entry Point - E-Learning Platform
 * Author: Smart E-Learning Team
 * Version: 1.0.0
 */

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Load configuration
$configPath = __DIR__ . '/../config/config.php';
if (!file_exists($configPath)) {
    die("Error: config.php not found at: " . $configPath);
}
require_once $configPath;

// Load core classes
$coreFiles = [
    'Database.php',
    'Model.php',
    'Controller.php',
    'Router.php'
];

foreach ($coreFiles as $file) {
    $filePath = ROOT_PATH . 'core/' . $file;
    if (!file_exists($filePath)) {
        die("Error: {$file} not found at: {$filePath}");
    }
    require_once $filePath;
}

// Initialize router
try {
    $router = new Router();
} catch (Exception $e) {
    die("Error initializing router: " . $e->getMessage());
}
