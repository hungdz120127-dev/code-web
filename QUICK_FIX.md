# ? QUICK FIX - S?a l?i "Not Found" ngay l?p t?c

## ?? Gi?i ph?p nhanh nh?t

### ? C?ch 1: Truy c?p tr?c ti?p (100% ho?t ??ng)

```
http://localhost/elearning/public/
```

??y l? c?ch ch?c ch?n nh?t, b? qua .htaccess!

---

## ?? C?ch 2: S?a l?i mod_rewrite

### Windows XAMPP:

**B??c 1:** M? file
```
C:\xampp\apache\conf\httpd.conf
```

**B??c 2:** T?m d?ng (Ctrl+F):
```
#LoadModule rewrite_module modules/mod_rewrite.so
```

**B??c 3:** X?a d?u `#` ? ??u:
```
LoadModule rewrite_module modules/mod_rewrite.so
```

**B??c 4:** T?m t?t c? (c? nhi?u ch?):
```
AllowOverride None
```

**B??c 5:** ??i TO?N B? th?nh:
```
AllowOverride All
```

**B??c 6:** L?u file (Ctrl+S)

**B??c 7:** M? XAMPP Control Panel ? Stop Apache ? Start Apache

**B??c 8:** Th? l?i:
```
http://localhost/elearning/
```

---

## ?? C?ch 3: S?a BASE_URL

**M? file:** `config/config.php`

**T?m d?ng:**
```php
define('BASE_URL', 'http://localhost/elearning/');
```

**??m b?o:**
- ??ng t?n th? m?c (elearning)
- C? d?u `/` ? cu?i
- Kh?ng c? `public/` trong ??

---

## ?? C?ch 4: Ki?m tra Database

**B??c 1:** Truy c?p
```
http://localhost/phpmyadmin
```

**B??c 2:** Ki?m tra database `elearning_db` c? ch?a?
- C? r?i ? OK
- Ch?a c? ? T?o m?i:
  ```sql
  CREATE DATABASE elearning_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
  ```

**B??c 3:** Click database `elearning_db`

**B??c 4:** Click tab "Import"

**B??c 5:** Ch?n file:
```
C:\xampp\htdocs\elearning\config\database.sql
```

**B??c 6:** Click "Go"

**B??c 7:** ??i import xong (c? 28 tables)

---

## ?? Test t?ng b??c

### Test 1: Apache & PHP
```
http://localhost/
```
? Ph?i th?y trang XAMPP Dashboard

### Test 2: PHP info
T?o file: `C:\xampp\htdocs\test.php`
```php
<?php phpinfo(); ?>
```
Truy c?p: `http://localhost/test.php`
? Ph?i th?y PHP info page

### Test 3: phpMyAdmin
```
http://localhost/phpmyadmin
```
? Ph?i v?o ???c

### Test 4: Th? m?c d? ?n
```
http://localhost/elearning/public/
```
? Ph?i th?y trang ch? E-Learning

---

## ?? L?i th??ng g?p & Gi?i ph?p

### L?i 1: "Not Found"
**Gi?i ph?p:**
```
Truy c?p: http://localhost/elearning/public/
```

### L?i 2: "Object not found"
**Gi?i ph?p:**
- Ki?m tra th? m?c ??ng: `C:\xampp\htdocs\elearning\`
- Ki?m tra file `public/index.php` c? t?n t?i

### L?i 3: "Database connection failed"
**Gi?i ph?p:**
```php
// S?a config/config.php
define('DB_HOST', 'localhost');
define('DB_NAME', 'elearning_db');
define('DB_USER', 'root');
define('DB_PASS', '');  // ?? tr?ng!
```

### L?i 4: Trang tr?ng (blank page)
**Gi?i ph?p:**
Th?m v?o ??u `public/index.php`:
```php
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

### L?i 5: CSS/JS kh?ng load
**Gi?i ph?p:**
```php
// Ki?m tra BASE_URL trong config.php
define('BASE_URL', 'http://localhost/elearning/');
// PH?I c? d?u / ? cu?i!
```

---

## ? Checklist 5 ph?t

```
[ ] 1. Apache ?ang ch?y? (XAMPP Control Panel)
[ ] 2. MySQL ?ang ch?y? (XAMPP Control Panel)
[ ] 3. Th? m?c ??ng? (C:\xampp\htdocs\elearning\)
[ ] 4. Database ?? import? (phpMyAdmin c? elearning_db)
[ ] 5. Truy c?p: http://localhost/elearning/public/
```

N?u 5 b??c tr?n OK ? Website ch?c ch?n ch?y!

---

## ?? Login ngay

```
URL: http://localhost/elearning/public/

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

## ?? V?n kh?ng ???c?

### Debug Mode:

**Th?m v?o:** `public/index.php` (d?ng ??u ti?n)

```php
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Debug Info</h2>";
echo "PHP Version: " . PHP_VERSION . "<br>";
echo "Current Dir: " . __DIR__ . "<br>";
echo "Config exists: " . (file_exists(__DIR__ . '/../config/config.php') ? 'YES' : 'NO') . "<br>";
echo "BASE_URL: " . (defined('BASE_URL') ? BASE_URL : 'Not defined') . "<br>";

die("Debug mode active");
```

Truy c?p v? xem th?ng tin ?? t?m l?i!

---

## ?? Success!

Sau khi v?o ???c:
1. ? ??ng nh?p
2. ? Xem Dashboard
3. ? Test t?nh n?ng
4. ? B?t ??u s? d?ng!

**Happy Learning! ??**
