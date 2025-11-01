# ?? Test Access - Ki?m tra truy c?p

## ? C?c URL ?? test

### 1. Truy c?p tr?c ti?p (Khuy?n d?ng)
```
http://localhost/elearning/public/
```
**? Th? URL n?y tr??c ti?n!**

### 2. V?i URL rewriting (sau khi b?t mod_rewrite)
```
http://localhost/elearning/
```

### 3. Test file index.php tr?c ti?p
```
http://localhost/elearning/public/index.php
```

---

## ?? Ki?m tra t?ng b??c

### B??c 1: Test Apache
```
http://localhost/
```
**K?t qu? mong ??i:** Trang XAMPP Dashboard

---

### B??c 2: Test th? m?c
```
http://localhost/elearning/
```
**K?t qu?:**
- N?u redirect ??n `public/` ? OK
- N?u 404 ? Th? m?c sai ho?c kh?ng t?n t?i

---

### B??c 3: Test public folder
```
http://localhost/elearning/public/
```
**K?t qu? mong ??i:** Trang ch? E-Learning
**N?u l?i:** Xem section "Debug" b?n d??i

---

## ?? Debug - N?u g?p l?i

### L?i 1: "config.php not found"

**Nguy?n nh?n:** File config kh?ng t?m th?y

**Gi?i ph?p:**
```bash
# Ki?m tra file c? t?n t?i kh?ng
C:\xampp\htdocs\elearning\config\config.php
```

N?u kh?ng c?, t?o l?i file `config/config.php`:
```php
<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'elearning_db');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

define('BASE_URL', 'http://localhost/elearning/');
define('ROOT_PATH', dirname(__DIR__) . '/');
define('PUBLIC_PATH', ROOT_PATH . 'public/');
define('UPLOAD_PATH', PUBLIC_PATH . 'uploads/');

define('SESSION_LIFETIME', 3600 * 24);
define('APP_NAME', 'Smart E-Learning Platform');
define('APP_VERSION', '1.0.0');
define('DEFAULT_LANG', 'vi');

define('PASSWORD_HASH_ALGO', PASSWORD_BCRYPT);
define('PASSWORD_HASH_COST', 12);

define('MAX_FILE_SIZE', 10 * 1024 * 1024);
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/gif', 'image/webp']);
define('ALLOWED_DOC_TYPES', ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document']);

date_default_timezone_set('Asia/Ho_Chi_Minh');
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
```

---

### L?i 2: "Database.php not found"

**Ki?m tra c?u tr?c th? m?c:**
```
C:\xampp\htdocs\elearning\
??? config/
?   ??? config.php
??? core/
?   ??? Database.php
?   ??? Model.php
?   ??? Controller.php
?   ??? Router.php
??? public/
?   ??? index.php
```

N?u thi?u file n?o, copy l?i t? source code g?c.

---

### L?i 3: Trang tr?ng (Blank Page)

**Nguy?n nh?n:** L?i PHP kh?ng hi?n th?

**Gi?i ph?p:** Th?m v?o ??u `public/index.php`:
```php
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

Sau ?? refresh l?i trang ?? xem l?i.

---

### L?i 4: "Call to undefined function"

**Nguy?n nh?n:** PHP extension ch?a b?t

**Gi?i ph?p:**
1. M?: `C:\xampp\php\php.ini`
2. T?m v? b? d?u `;` ? c?c extension:
```ini
;extension=pdo_mysql  ? extension=pdo_mysql
;extension=mbstring   ? extension=mbstring
;extension=openssl    ? extension=openssl
```
3. Save v? restart Apache

---

### L?i 5: "Database connection failed"

**Gi?i ph?p:**

1. **Ki?m tra MySQL ?ang ch?y:**
   - M? XAMPP Control Panel
   - MySQL ph?i c? status "Running"

2. **Ki?m tra database t?n t?i:**
   - Truy c?p: http://localhost/phpmyadmin
   - T?m database: `elearning_db`
   - N?u kh?ng c? ? Import l?i file `config/database.sql`

3. **Ki?m tra th?ng tin k?t n?i:**
```php
// Trong config/config.php
define('DB_HOST', 'localhost');
define('DB_NAME', 'elearning_db');
define('DB_USER', 'root');
define('DB_PASS', '');  // Ph?i ?? tr?ng v?i XAMPP m?c ??nh
```

---

## ?? Test Script

T?o file: `C:\xampp\htdocs\elearning\test.php`

```php
<?php
echo "<h2>System Check</h2>";

// Test 1: PHP Version
echo "<p>? PHP Version: " . PHP_VERSION . "</p>";

// Test 2: Config file
$configExists = file_exists(__DIR__ . '/config/config.php');
echo "<p>" . ($configExists ? "?" : "?") . " Config file exists</p>";

// Test 3: Core files
$coreFiles = ['Database.php', 'Model.php', 'Controller.php', 'Router.php'];
foreach ($coreFiles as $file) {
    $exists = file_exists(__DIR__ . '/core/' . $file);
    echo "<p>" . ($exists ? "?" : "?") . " core/{$file} exists</p>";
}

// Test 4: Public folder
$publicExists = file_exists(__DIR__ . '/public/index.php');
echo "<p>" . ($publicExists ? "?" : "?") . " public/index.php exists</p>";

// Test 5: Database connection
if ($configExists) {
    require_once __DIR__ . '/config/config.php';
    try {
        $pdo = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET,
            DB_USER,
            DB_PASS,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
        echo "<p>? Database connection: SUCCESS</p>";
        
        // Count tables
        $stmt = $pdo->query("SHOW TABLES");
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        echo "<p>? Database tables: " . count($tables) . " tables found</p>";
        
    } catch (PDOException $e) {
        echo "<p>? Database connection: FAILED<br>";
        echo "Error: " . $e->getMessage() . "</p>";
    }
}

echo "<hr>";
echo "<h3>If all checks pass, access:</h3>";
echo "<p><a href='http://localhost/elearning/public/'>http://localhost/elearning/public/</a></p>";
```

**Ch?y test:**
```
http://localhost/elearning/test.php
```

Xem k?t qu? ?? bi?t v?n ?? ? ??u!

---

## ? K?t qu? mong ??i

Khi truy c?p th?nh c?ng, b?n s? th?y:

### Trang ch? E-Learning:
- ?? Hero section m?u xanh v?i ti?u ??
- ?? Navigation bar (Home, Kh?a h?c, ??ng nh?p, ??ng k?)
- ?? C?c kh?a h?c ph? bi?n
- ?? Th?ng k? (500+ kh?a h?c, 10K+ h?c vi?n...)
- ?? Footer ? d??i

### Dashboard (sau khi ??ng nh?p):
- ?? Cards th?ng k?
- ?? Kh?a h?c c?a b?n
- ?? Ti?n ?? h?c t?p
- ?? Th?ng b?o

---

## ?? ??ng nh?p test

Sau khi import database xong:

```
URL: http://localhost/elearning/public/index.php?url=auth/login

Admin:
Email: admin@elearning.com
Password: admin123

Teacher:
Email: teacher@elearning.com
Password: admin123

Student:
Email: student@elearning.com
Password: admin123
```

---

## ?? Troubleshooting Steps

### Step 1: Clear Browser Cache
- Ctrl + Shift + Delete
- Clear cache and cookies

### Step 2: Restart Services
- Stop Apache ? Start Apache
- Stop MySQL ? Start MySQL

### Step 3: Check Ports
- Apache: Port 80 (kh?ng b? conflict)
- MySQL: Port 3306 (kh?ng b? conflict)

### Step 4: Check Firewall
- Allow Apache through Windows Firewall
- Allow MySQL through Windows Firewall

### Step 5: Check Antivirus
- T?m th?i disable antivirus
- Test l?i website

---

## ?? Final Check

```
? Apache running?
? MySQL running?
? Database imported?
? Files in correct location?
? BASE_URL correct?
? mod_rewrite enabled (optional)?

If ALL checked ? Website MUST work!
```

---

**Try now:** http://localhost/elearning/public/

**Need help?** Check error logs:
- `C:\xampp\apache\logs\error.log`
- `C:\xampp\mysql\data\*.err`

**Good luck! ??**
