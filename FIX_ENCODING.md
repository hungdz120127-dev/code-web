# ?? FIX ENCODING - S?a l?i ti?ng Vi?t

## ? L?i g?p ph?i:

```
Fatal error: Undefined constant "ROOT_PATH"
Ti?ng Vi?t hi?n th? d?u ? ho?c k? t? l?i
```

## ? ?? S?A:

### 1. File `config/config.php`
- ? Chuy?n sang UTF-8 without BOM
- ? B? d?u ti?ng Vi?t trong comment (d?ng kh?ng d?u)
- ? ??m b?o file load ???c ??ng

### 2. File `public/index.php`
- ? Define ROOT_PATH TR??C KHI load config
- ? Th?m UTF-8 header
- ? Th?m error checking r? r?ng

### 3. Git Configuration
- ? T?o `.gitattributes` ?? force UTF-8 encoding
- ? T?o `.editorconfig` cho consistent coding style

---

## ?? GI?I PH?P NGAY:

### C?ch 1: Truy c?p ngay (Nhanh nh?t)
```
http://localhost/elearning/public/
```

### C?ch 2: N?u v?n l?i encoding

**B??c 1: Ki?m tra file config**
```
C:\xampp\htdocs\elearning\config\config.php
```

**M? b?ng Notepad++:**
1. File ? Open
2. Ch?n `config.php`
3. Menu: Encoding ? Encode in UTF-8 (without BOM)
4. Save

**B??c 2: Ki?m tra php.ini**
```
C:\xampp\php\php.ini
```

T?m v? s?a:
```ini
; T?m d?ng n?y
default_charset = "UTF-8"

; ??m b?o kh?ng c? d?u ; ? ??u
```

**B??c 3: Restart Apache**
- Stop Apache
- Start Apache

---

## ?? Nguy?n nh?n:

1. **File config.php c? BOM (Byte Order Mark)**
   - Windows text editor th??ng th?m BOM v?o UTF-8
   - PHP kh?ng parse ???c khi c? BOM
   - ? Ph?i d?ng UTF-8 **without BOM**

2. **ROOT_PATH kh?ng ???c define**
   - File config.php d?ng `dirname(__DIR__)` ?? define ROOT_PATH
   - Nh?ng n?u config kh?ng load ???c, ROOT_PATH = undefined
   - ? Ph?i define ROOT_PATH TR??C KHI load config

3. **Encoding kh?ng nh?t qu?n**
   - M?t s? file UTF-8, m?t s? ANSI
   - ? Ph?i force t?t c? file UTF-8

---

## ?? C?ch s?a th? c?ng (n?u c?n):

### S?a `config/config.php`:

**Thay T?T C? ti?ng Vi?t th?nh kh?ng d?u:**

```php
<?php
// Cau hinh Database (kh?ng d?u)
define('DB_HOST', 'localhost');
define('DB_NAME', 'elearning_db');
define('DB_USER', 'root');
define('DB_PASS', '');

// Cau hinh duong dan (kh?ng d?u)
define('BASE_URL', 'http://localhost/elearning/');
define('ROOT_PATH', dirname(__DIR__) . '/');
define('PUBLIC_PATH', ROOT_PATH . 'public/');

// Cau hinh ung dung (kh?ng d?u)
define('APP_NAME', 'Smart E-Learning Platform');
define('APP_VERSION', '1.0.0');

// Mui gio (kh?ng d?u)
date_default_timezone_set('Asia/Ho_Chi_Minh');

// Bat hien thi loi (kh?ng d?u)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Khoi dong session (kh?ng d?u)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
```

### S?a `public/index.php`:

```php
<?php
// Set UTF-8 encoding
header('Content-Type: text/html; charset=UTF-8');

// Define ROOT_PATH FIRST
define('ROOT_PATH', dirname(__DIR__) . '/');

// Then load config
require_once ROOT_PATH . 'config/config.php';

// Load core
require_once ROOT_PATH . 'core/Database.php';
require_once ROOT_PATH . 'core/Model.php';
require_once ROOT_PATH . 'core/Controller.php';
require_once ROOT_PATH . 'core/Router.php';

// Initialize
$router = new Router();
```

---

## ?? Test ngay:

```
http://localhost/elearning/public/test.php
```

N?u test pass ? Website ?? OK!

---

## ?? Best Practices:

### 1. Lu?n d?ng UTF-8 without BOM
- Notepad++: Encoding ? UTF-8 (without BOM)
- VS Code: T? ??ng UTF-8 without BOM
- Sublime: Save with Encoding ? UTF-8

### 2. Tr?nh ti?ng Vi?t trong code
- Comment: D?ng kh?ng d?u ho?c English
- Variable: Ch? d?ng English
- Function: Ch? d?ng English
- Ti?ng Vi?t ch? d?ng trong: Database content, Views (HTML)

### 3. Git settings
```bash
# Set Git to handle UTF-8
git config --global core.autocrlf false
git config --global core.eol lf
git config --global core.quotepath off
```

---

## ?? Checklist:

- ? File config.php: UTF-8 without BOM
- ? File index.php: UTF-8 without BOM
- ? T?t c? file .php: UTF-8 without BOM
- ? ROOT_PATH defined tr??c khi load config
- ? php.ini: default_charset = "UTF-8"
- ? Apache restarted

---

## ?? K?t qu?:

Sau khi s?a:
- ? Website ch?y b?nh th??ng
- ? Kh?ng c?n l?i encoding
- ? Ti?ng Vi?t hi?n th? ??ng
- ? Git commit kh?ng b? l?i

---

**Th? ngay:** http://localhost/elearning/public/

**N?u v?n l?i:** Ch?y test script ?? debug:
```
http://localhost/elearning/public/test.php
```
