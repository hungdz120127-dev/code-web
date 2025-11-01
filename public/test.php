<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Test - E-Learning Platform</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; padding: 40px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #4361ee; border-bottom: 3px solid #4361ee; padding-bottom: 10px; }
        .test-item { padding: 15px; margin: 10px 0; border-radius: 5px; display: flex; align-items: center; }
        .success { background: #d4edda; border-left: 4px solid #28a745; }
        .error { background: #f8d7da; border-left: 4px solid #dc3545; }
        .warning { background: #fff3cd; border-left: 4px solid #ffc107; }
        .icon { font-size: 24px; margin-right: 15px; }
        .details { flex: 1; }
        .label { font-weight: bold; margin-bottom: 5px; }
        .value { color: #666; font-size: 14px; }
        .btn { display: inline-block; padding: 12px 24px; background: #4361ee; color: white; text-decoration: none; border-radius: 5px; margin: 10px 5px; }
        .btn:hover { background: #3730a3; }
    </style>
</head>
<body>
    <div class="container">
        <h1>?? E-Learning Platform - System Test</h1>
        
        <?php
        $allPassed = true;
        
        // Test 1: PHP Version
        echo '<div class="test-item ' . (PHP_VERSION_ID >= 80000 ? 'success' : 'warning') . '">';
        echo '<div class="icon">' . (PHP_VERSION_ID >= 80000 ? '?' : '??') . '</div>';
        echo '<div class="details">';
        echo '<div class="label">PHP Version</div>';
        echo '<div class="value">Current: ' . PHP_VERSION . ' (Required: 8.0+)</div>';
        echo '</div></div>';
        
        // Test 2: Config file
        $configPath = __DIR__ . '/../config/config.php';
        $configExists = file_exists($configPath);
        echo '<div class="test-item ' . ($configExists ? 'success' : 'error') . '">';
        echo '<div class="icon">' . ($configExists ? '?' : '?') . '</div>';
        echo '<div class="details">';
        echo '<div class="label">Configuration File</div>';
        echo '<div class="value">' . ($configExists ? 'Found' : 'NOT FOUND') . ': ' . $configPath . '</div>';
        echo '</div></div>';
        
        if (!$configExists) $allPassed = false;
        
        // Test 3: Core files
        $coreFiles = ['Database.php', 'Model.php', 'Controller.php', 'Router.php'];
        $coreOK = true;
        foreach ($coreFiles as $file) {
            $filePath = dirname(__DIR__) . '/core/' . $file;
            if (!file_exists($filePath)) {
                $coreOK = false;
                $allPassed = false;
            }
        }
        echo '<div class="test-item ' . ($coreOK ? 'success' : 'error') . '">';
        echo '<div class="icon">' . ($coreOK ? '?' : '?') . '</div>';
        echo '<div class="details">';
        echo '<div class="label">Core Files</div>';
        echo '<div class="value">' . ($coreOK ? 'All 4 core files found' : 'Some core files missing') . '</div>';
        echo '</div></div>';
        
        // Test 4: Database connection
        if ($configExists) {
            require_once $configPath;
            
            try {
                $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
                $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                ]);
                
                // Count tables
                $stmt = $pdo->query("SHOW TABLES");
                $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
                $tableCount = count($tables);
                
                echo '<div class="test-item success">';
                echo '<div class="icon">?</div>';
                echo '<div class="details">';
                echo '<div class="label">Database Connection</div>';
                echo '<div class="value">Connected to: ' . DB_NAME . ' (' . $tableCount . ' tables found)</div>';
                echo '</div></div>';
                
                // Check essential tables
                $requiredTables = ['users', 'courses', 'lessons', 'enrollments'];
                $missingTables = [];
                foreach ($requiredTables as $table) {
                    if (!in_array($table, $tables)) {
                        $missingTables[] = $table;
                    }
                }
                
                if (count($missingTables) > 0) {
                    echo '<div class="test-item error">';
                    echo '<div class="icon">?</div>';
                    echo '<div class="details">';
                    echo '<div class="label">Required Tables</div>';
                    echo '<div class="value">Missing: ' . implode(', ', $missingTables) . '</div>';
                    echo '</div></div>';
                    $allPassed = false;
                } else {
                    echo '<div class="test-item success">';
                    echo '<div class="icon">?</div>';
                    echo '<div class="details">';
                    echo '<div class="label">Required Tables</div>';
                    echo '<div class="value">All essential tables present</div>';
                    echo '</div></div>';
                }
                
            } catch (PDOException $e) {
                echo '<div class="test-item error">';
                echo '<div class="icon">?</div>';
                echo '<div class="details">';
                echo '<div class="label">Database Connection</div>';
                echo '<div class="value">FAILED: ' . htmlspecialchars($e->getMessage()) . '</div>';
                echo '</div></div>';
                $allPassed = false;
            }
        }
        
        // Test 5: Writable directories
        $writableDirs = [
            'public/uploads',
            'public/uploads/avatars',
            'public/uploads/documents',
            'public/uploads/certificates',
            'backups'
        ];
        
        $writableOK = true;
        foreach ($writableDirs as $dir) {
            $dirPath = dirname(__DIR__) . '/' . $dir;
            if (!is_writable($dirPath)) {
                $writableOK = false;
            }
        }
        
        echo '<div class="test-item ' . ($writableOK ? 'success' : 'warning') . '">';
        echo '<div class="icon">' . ($writableOK ? '?' : '??') . '</div>';
        echo '<div class="details">';
        echo '<div class="label">Upload Directories</div>';
        echo '<div class="value">' . ($writableOK ? 'All directories writable' : 'Some directories not writable') . '</div>';
        echo '</div></div>';
        
        // Test 6: mod_rewrite
        echo '<div class="test-item warning">';
        echo '<div class="icon">??</div>';
        echo '<div class="details">';
        echo '<div class="label">URL Rewriting (mod_rewrite)</div>';
        echo '<div class="value">Status unknown - Test by accessing homepage</div>';
        echo '</div></div>';
        
        // Summary
        echo '<hr>';
        if ($allPassed) {
            echo '<div style="background: #d4edda; padding: 20px; border-radius: 5px; text-align: center;">';
            echo '<h2 style="color: #28a745; margin: 0;">?? All Tests Passed!</h2>';
            echo '<p>System is ready to use.</p>';
            echo '</div>';
        } else {
            echo '<div style="background: #f8d7da; padding: 20px; border-radius: 5px; text-align: center;">';
            echo '<h2 style="color: #dc3545; margin: 0;">?? Some Tests Failed</h2>';
            echo '<p>Please fix the errors above before proceeding.</p>';
            echo '</div>';
        }
        
        // Access links
        echo '<hr>';
        echo '<h3>?? Access URLs:</h3>';
        echo '<a href="http://localhost/elearning/public/" class="btn">?? Homepage</a>';
        echo '<a href="http://localhost/elearning/public/index.php?url=auth/login" class="btn">?? Login</a>';
        echo '<a href="http://localhost/phpmyadmin" class="btn" target="_blank">??? phpMyAdmin</a>';
        
        echo '<hr>';
        echo '<h3>?? Need Help?</h3>';
        echo '<p>Check these files for detailed instructions:</p>';
        echo '<ul>';
        echo '<li><strong>QUICK_FIX.md</strong> - Quick solutions</li>';
        echo '<li><strong>SETUP_GUIDE.md</strong> - Step-by-step setup</li>';
        echo '<li><strong>INSTALL.md</strong> - Detailed installation</li>';
        echo '<li><strong>TEST_ACCESS.md</strong> - Testing guide</li>';
        echo '</ul>';
        ?>
        
        <hr>
        <p style="text-align: center; color: #666; font-size: 14px;">
            E-Learning Platform v1.0.0 | System Test Script
        </p>
    </div>
</body>
</html>
