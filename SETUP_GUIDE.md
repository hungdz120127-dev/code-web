# ?? Setup Guide - H??ng d?n c?i ??t nhanh

## ?? L?i "Not Found" - Gi?i ph?p

### Nguy?n nh?n
- File .htaccess kh?ng ho?t ??ng
- mod_rewrite ch?a ???c b?t
- BASE_URL ch?a ??ng

---

## ?? C?c b??c c?i ??t

### B??c 1: Copy v?o XAMPP

```
Windows: C:\xampp\htdocs\elearning\
Linux: /opt/lampp/htdocs/elearning/
```

### B??c 2: B?t mod_rewrite

#### Windows XAMPP:
1. M? file: `C:\xampp\apache\conf\httpd.conf`
2. T?m d?ng: `#LoadModule rewrite_module modules/mod_rewrite.so`
3. X?a d?u `#` ? ??u d?ng
4. T?m t?t c? `AllowOverride None` 
5. ??i th?nh: `AllowOverride All`
6. L?u file
7. Restart Apache trong XAMPP Control Panel

#### Linux:
```bash
sudo a2enmod rewrite
sudo service apache2 restart
```

### B??c 3: T?o Database

1. M? phpMyAdmin: `http://localhost/phpmyadmin`
2. T?o database: `elearning_db`
3. Click "Import"
4. Ch?n file: `config/database.sql`
5. Click "Go"

### B??c 4: C?u h?nh

M? file: `config/config.php`

Ch?nh BASE_URL theo th? m?c c?a b?n:

```php
// N?u ??t trong: C:\xampp\htdocs\elearning
define('BASE_URL', 'http://localhost/elearning/');

// N?u ??t trong: C:\xampp\htdocs\myproject
define('BASE_URL', 'http://localhost/myproject/');
```

### B??c 5: Ki?m tra Database

```php
// File: config/config.php

define('DB_HOST', 'localhost');
define('DB_NAME', 'elearning_db');
define('DB_USER', 'root');
define('DB_PASS', '');  // ?? tr?ng n?u d?ng XAMPP m?c ??nh
```

### B??c 6: Truy c?p

M? tr?nh duy?t v? truy c?p:

```
http://localhost/elearning/
```

HO?C (n?u .htaccess ho?t ??ng):

```
http://localhost/elearning/public/
```

---

## ?? X? l? l?i th??ng g?p

### L?i 1: "Not Found"

**Gi?i ph?p A: Truy c?p tr?c ti?p public**
```
http://localhost/elearning/public/
```

**Gi?i ph?p B: Ki?m tra mod_rewrite**
```
1. M?: C:\xampp\apache\conf\httpd.conf
2. T?m: LoadModule rewrite_module
3. ??m b?o KH?NG c? d?u # ? ??u
4. Restart Apache
```

**Gi?i ph?p C: Ki?m tra AllowOverride**
```
1. M?: C:\xampp\apache\conf\httpd.conf
2. T?m t?t c?: AllowOverride None
3. ??i th?nh: AllowOverride All
4. Restart Apache
```

### L?i 2: "Database connection failed"

```php
// Ki?m tra config/config.php
define('DB_NAME', 'elearning_db');  // T?n database ??ng ch?a?
define('DB_USER', 'root');          // Username ??ng ch?a?
define('DB_PASS', '');              // Password (?? tr?ng v?i XAMPP)
```

**Gi?i ph?p:**
```
1. M? phpMyAdmin
2. Ki?m tra database 'elearning_db' ?? t?o ch?a
3. Import l?i file database.sql n?u c?n
```

### L?i 3: "Warning: require_once..."

```php
// Ki?m tra BASE_URL trong config.php
define('BASE_URL', 'http://localhost/elearning/');

// Ki?m tra ROOT_PATH
define('ROOT_PATH', dirname(__DIR__) . '/');
```

### L?i 4: Kh?ng load ???c CSS/JS

**Ki?m tra BASE_URL:**
```php
// Trong config.php
define('BASE_URL', 'http://localhost/elearning/');
// Ph?i c? d?u / ? cu?i!
```

**Ki?m tra trong header.php:**
```html
<link rel="stylesheet" href="<?= BASE_URL ?>public/css/style.css">
<!-- Ph?i ??ng nh? th? n?y -->
```

---

## ?? C?u tr?c th? m?c ph?i ??ng

```
C:\xampp\htdocs\elearning\
??? config/
?   ??? config.php
?   ??? database.sql
??? core/
??? models/
??? controllers/
??? views/
??? public/
?   ??? index.php  ? Entry point ch?nh
?   ??? css/
?   ??? js/
?   ??? uploads/
??? .htaccess
??? index.php  ? Redirect file
```

---

## ? Ki?m tra t?ng b??c

### Step 1: Ki?m tra Apache & MySQL
```
1. M? XAMPP Control Panel
2. Apache: Status = "Running" (Port 80)
3. MySQL: Status = "Running" (Port 3306)
```

### Step 2: Test PHP
T?o file test: `C:\xampp\htdocs\test.php`
```php
<?php
phpinfo();
```
Truy c?p: `http://localhost/test.php`
N?u hi?n trang PHP info ? PHP OK!

### Step 3: Test Database
Truy c?p: `http://localhost/phpmyadmin`
- Th?y phpMyAdmin ? MySQL OK!
- Ki?m tra database `elearning_db` c? t?n t?i ch?a

### Step 4: Test mod_rewrite
T?o file: `C:\xampp\htdocs\elearning\test-rewrite.php`
```php
<?php
echo "Rewrite works!";
```

Truy c?p: `http://localhost/elearning/test-rewrite`
- N?u hi?n "Rewrite works!" ? mod_rewrite OK!
- N?u l?i 404 ? mod_rewrite ch?a ho?t ??ng

---

## ?? URL ??ng ?? truy c?p

### Option 1: Truy c?p tr?c ti?p (LU?N HO?T ??NG)
```
http://localhost/elearning/public/
```

### Option 2: V?i .htaccess (c?n mod_rewrite)
```
http://localhost/elearning/
```

### Login URLs:
```
http://localhost/elearning/public/
ho?c
http://localhost/elearning/public/index.php?url=auth/login
```

---

## ?? T?i kho?n ??ng nh?p

Sau khi import database th?nh c?ng:

| Role | Email | Password |
|------|-------|----------|
| **Admin** | admin@elearning.com | admin123 |
| **Teacher** | teacher@elearning.com | admin123 |
| **Student** | student@elearning.com | admin123 |

---

## ?? V?n kh?ng ???c?

### Th? c?ch n?y:

1. **Truy c?p tr?c ti?p file index.php:**
```
http://localhost/elearning/public/index.php
```

2. **N?u th?y trang ch? ? Success!**
   - V?n ?? l? ? .htaccess
   - B?t mod_rewrite theo h??ng d?n tr?n

3. **N?u v?n l?i:**
   - Check BASE_URL trong config.php
   - Check database ?? import ch?a
   - Check quy?n th? m?c (Linux: chmod 755)

---

## ?? Debug Mode

Th?m v?o ??u file `public/index.php`:

```php
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Current directory: " . __DIR__ . "<br>";
echo "File exists: " . (file_exists('../config/config.php') ? 'YES' : 'NO') . "<br>";
die("Debug info shown");
```

Truy c?p xem th?ng tin debug ?? t?m l?i.

---

## ? Checklist ho?n ch?nh

- [ ] Apache ?ang ch?y (Port 80)
- [ ] MySQL ?ang ch?y (Port 3306)
- [ ] Database `elearning_db` ?? t?o
- [ ] File `database.sql` ?? import
- [ ] mod_rewrite ?? b?t
- [ ] AllowOverride = All
- [ ] BASE_URL ??ng trong config.php
- [ ] DB_NAME ??ng trong config.php
- [ ] Th? m?c ??ng: C:\xampp\htdocs\elearning\
- [ ] File .htaccess c? trong th? m?c
- [ ] Truy c?p: http://localhost/elearning/public/

---

## ?? Sau khi c?i ??t th?nh c?ng

1. ??ng nh?p v?i t?i kho?n admin
2. ??i m?t kh?u m?c ??nh
3. T?o kh?a h?c m?u
4. Th? nghi?m c?c t?nh n?ng
5. Customize theo ? b?n

---

**Good luck! ??**

N?u v?n g?p v?n ??, h?y ki?m tra:
- Apache error log: `C:\xampp\apache\logs\error.log`
- PHP error log trong XAMPP
