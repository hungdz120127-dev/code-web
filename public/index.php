<?php
/**
 * Entry Point - E-Learning Platform
 * Author: Smart E-Learning Team
 * Version: 1.0.0
 */

// Set UTF-8 encoding
header('Content-Type: text/html; charset=UTF-8');
mb_internal_encoding('UTF-8');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Define paths first (before loading config)
define('ROOT_PATH', dirname(__DIR__) . '/');

// Load configuration
$configPath = ROOT_PATH . 'config/config.php';
if (!file_exists($configPath)) {
    die("ERROR: config.php not found!<br>Expected at: " . $configPath . "<br><br>Please make sure the file exists.");
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
        die("ERROR: {$file} not found!<br>Expected at: {$filePath}");
    }
    require_once $filePath;
}

// Initialize router
try {
    $router = new Router();
} catch (Exception $e) {
    die("ERROR initializing router: " . $e->getMessage());
}
