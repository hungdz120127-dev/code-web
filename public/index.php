&lt;?php
/**
 * Entry Point - E-Learning Platform
 * Author: Smart E-Learning Team
 * Version: 1.0.0
 */

// Load configuration
require_once __DIR__ . '/../config/config.php';

// Load core classes
require_once ROOT_PATH . 'core/Database.php';
require_once ROOT_PATH . 'core/Model.php';
require_once ROOT_PATH . 'core/Controller.php';
require_once ROOT_PATH . 'core/Router.php';

// Initialize router
$router = new Router();
