# ?? H??ng D?n C?i ??t Chi Ti?t

## ?? M?c l?c
1. [Y?u c?u h? th?ng](#y?u-c?u-h?-th?ng)
2. [C?i ??t XAMPP](#c?i-??t-xampp)
3. [C?i ??t d? ?n](#c?i-??t-d?-?n)
4. [C?u h?nh Database](#c?u-h?nh-database)
5. [C?u h?nh ?ng d?ng](#c?u-h?nh-?ng-d?ng)
6. [Ki?m tra v? ch?y](#ki?m-tra-v?-ch?y)
7. [X? l? l?i th??ng g?p](#x?-l?-l?i-th??ng-g?p)

---

## 1?? Y?u c?u h? th?ng

### Ph?n m?m b?t bu?c:
- ? **XAMPP** v8.0+ (ho?c WAMP/LAMP/MAMP)
- ? **PHP** >= 8.0
- ? **MySQL/MariaDB** >= 5.7
- ? **Apache** Web Server

### Extensions PHP c?n c?:
```
? pdo
? pdo_mysql
? mbstring
? fileinfo
? gd
? json
? session
? curl (optional)
```

### Ki?m tra PHP version:
```bash
php -v
```

K?t qu? mong ??i:
```
PHP 8.0.x (cli) (built: ...)
```

---

## 2?? C?i ??t XAMPP

### Windows:

1. **Download XAMPP**
   - Truy c?p: https://www.apachefriends.org/
   - Download phi?n b?n PHP 8.0+

2. **C?i ??t**
   - Ch?y file installer
   - Ch?n th? m?c c?i ??t: `C:\xampp`
   - Ch?n components: Apache, MySQL, PHP, phpMyAdmin

3. **Kh?i ??ng XAMPP**
   - M? **XAMPP Control Panel**
   - Click **Start** cho Apache v? MySQL
   - Ki?m tra: Apache (Port 80), MySQL (Port 3306)

### Linux:

```bash
# Download
wget https://www.apachefriends.org/xampp-files/8.0.x/xampp-linux-x64-8.0.x-installer.run

# Ph?n quy?n
chmod +x xampp-linux-x64-8.0.x-installer.run

# C?i ??t
sudo ./xampp-linux-x64-8.0.x-installer.run

# Kh?i ??ng
sudo /opt/lampp/lampp start
```

### macOS:

```bash
# Download t? website
# K?o th? XAMPP v?o Applications
# M? XAMPP Manager
# Start Apache & MySQL
```

---

## 3?? C?i ??t d? ?n

### B??c 1: Download source code

**Option A: Clone t? Git (n?u c?)**
```bash
cd C:\xampp\htdocs  # Windows
# ho?c
cd /opt/lampp/htdocs  # Linux
# ho?c
cd /Applications/XAMPP/htdocs  # macOS

git clone https://github.com/yourusername/elearning-platform.git elearning
```

**Option B: Download ZIP**
1. Download file ZIP
2. Gi?i n?n v?o th? m?c:
   - Windows: `C:\xampp\htdocs\elearning`
   - Linux: `/opt/lampp/htdocs/elearning`
   - macOS: `/Applications/XAMPP/htdocs/elearning`

### B??c 2: Ph?n quy?n th? m?c (Linux/macOS)

```bash
# Ph?n quy?n ??c/ghi
sudo chmod -R 755 /opt/lampp/htdocs/elearning

# Ph?n quy?n th? m?c uploads
sudo chmod -R 777 /opt/lampp/htdocs/elearning/public/uploads
sudo chmod -R 777 /opt/lampp/htdocs/elearning/backups

# ??i owner (optional)
sudo chown -R www-data:www-data /opt/lampp/htdocs/elearning
```

---

## 4?? C?u h?nh Database

### B??c 1: T?o Database

1. **M? phpMyAdmin**
   ```
   http://localhost/phpmyadmin
   ```

2. **??ng nh?p**
   - Username: `root`
   - Password: (?? tr?ng ho?c `root`)

3. **T?o Database**
   - Click tab **"Databases"**
   - Nh?p t?n: `elearning_db`
   - Collation: `utf8mb4_unicode_ci`
   - Click **Create**

### B??c 2: Import SQL

1. **Click v?o database `elearning_db`**

2. **Click tab "Import"**

3. **Choose File**
   - Browse ??n: `elearning/config/database.sql`
   - Click **Go**

4. **Ki?m tra**
   - Sau khi import, b?n s? th?y c?c b?ng:
     ```
     ? users
     ? courses
     ? chapters
     ? lessons
     ? enrollments
     ? quiz_questions
     ? quiz_results
     ? forum_topics
     ? forum_replies
     ? chat_messages
     ? badges
     ? certificates
     ... v? nhi?u b?ng kh?c
     ```

### B??c 3: T?o User Database (Optional - B?o m?t)

```sql
-- T?o user m?i
CREATE USER 'elearning_user'@'localhost' IDENTIFIED BY 'your_password_here';

-- C?p quy?n
GRANT ALL PRIVILEGES ON elearning_db.* TO 'elearning_user'@'localhost';

-- ?p d?ng
FLUSH PRIVILEGES;
```

---

## 5?? C?u h?nh ?ng d?ng

### B??c 1: M? file config

M? file: `elearning/config/config.php`

### B??c 2: C?u h?nh Database

```php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'elearning_db');
define('DB_USER', 'root');  // Ho?c 'elearning_user' n?u ?? t?o
define('DB_PASS', '');       // Ho?c password c?a b?n
define('DB_CHARSET', 'utf8mb4');
```

### B??c 3: C?u h?nh Base URL

**Quan tr?ng:** Ph?i ??ng v?i th? m?c c?a b?n!

```php
// N?u th? m?c l?: C:\xampp\htdocs\elearning
define('BASE_URL', 'http://localhost/elearning/');

// N?u th? m?c l?: C:\xampp\htdocs\myproject
define('BASE_URL', 'http://localhost/myproject/');

// N?u s? d?ng domain ri?ng
define('BASE_URL', 'http://elearning.local/');
```

### B??c 4: C?u h?nh paths

```php
define('ROOT_PATH', dirname(__DIR__) . '/');
define('PUBLIC_PATH', ROOT_PATH . 'public/');
define('UPLOAD_PATH', PUBLIC_PATH . 'uploads/');
```

### B??c 5: Ki?m tra .htaccess

**File: `elearning/.htaccess`**
```apache
RewriteEngine On
RewriteBase /

# Redirect to public folder
RewriteCond %{REQUEST_URI} !^/public/
RewriteRule ^(.*)$ public/$1 [L]
```

**File: `elearning/public/.htaccess`**
```apache
RewriteEngine On
RewriteBase /

# Don't rewrite files or directories
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d

# Rewrite everything else to index.php
RewriteRule ^(.+)$ index.php?url=$1 [QSA,L]
```

---

## 6?? Ki?m tra v? ch?y

### B??c 1: Ki?m tra Apache & MySQL

M? **XAMPP Control Panel**, ??m b?o:
- ? Apache: **Running** (Port 80)
- ? MySQL: **Running** (Port 3306)

### B??c 2: Test truy c?p

M? tr?nh duy?t, truy c?p:
```
http://localhost/elearning/
```

**K?t qu? mong ??i:**
- ? Trang ch? hi?n th? ??p
- ? Menu navigation ho?t ??ng
- ? Kh?ng c? l?i PHP

### B??c 3: Test ??ng nh?p

1. Click **"??ng nh?p"**
2. Nh?p:
   - Email: `admin@elearning.com`
   - Password: `admin123`
3. Click **??ng nh?p**

**K?t qu?:**
- ? Redirect v? Dashboard
- ? Hi?n th? th?ng tin user

### B??c 4: Ki?m tra t?nh n?ng

**Test List:**
- [ ] Xem danh s?ch kh?a h?c
- [ ] ??ng k? kh?a h?c (v?i t?i kho?n student)
- [ ] Xem b?i h?c
- [ ] L?m quiz
- [ ] G?i tin nh?n chat
- [ ] T?o topic di?n ??n
- [ ] Upload avatar

---

## 7?? X? l? l?i th??ng g?p

### ? L?i 1: "Database connection failed"

**Nguy?n nh?n:**
- MySQL ch?a ch?y
- Th?ng tin database sai

**Gi?i ph?p:**
```bash
# 1. Ki?m tra MySQL ?ang ch?y
# XAMPP Control Panel ? MySQL ? Start

# 2. Ki?m tra config.php
# DB_HOST = 'localhost' ?
# DB_NAME = 'elearning_db' ?
# DB_USER = 'root' ?
# DB_PASS = '' ?

# 3. Test k?t n?i t? terminal
mysql -u root -p
# Nh?p password (ho?c Enter n?u kh?ng c?)
SHOW DATABASES;
# Ph?i th?y 'elearning_db'
```

---

### ? L?i 2: "404 Not Found" ho?c "Page not found"

**Nguy?n nh?n:**
- .htaccess kh?ng ho?t ??ng
- mod_rewrite ch?a b?t

**Gi?i ph?p:**

**Windows:**
```
1. M?: C:\xampp\apache\conf\httpd.conf
2. T?m d?ng: #LoadModule rewrite_module modules/mod_rewrite.so
3. X?a d?u # ? ??u d?ng
4. T?m t?t c?: AllowOverride None
5. ??i th?nh: AllowOverride All
6. Restart Apache
```

**Linux:**
```bash
# B?t mod_rewrite
sudo a2enmod rewrite

# Restart Apache
sudo service apache2 restart
```

---

### ? L?i 3: "Permission denied" khi upload file

**Nguy?n nh?n:**
- Th? m?c uploads kh?ng c? quy?n ghi

**Gi?i ph?p:**

**Windows:**
```
1. Chu?t ph?i v?o th? m?c: public/uploads
2. Properties ? Security
3. Edit ? Add ? Everyone
4. Cho Full Control
5. Apply
```

**Linux/macOS:**
```bash
sudo chmod -R 777 /opt/lampp/htdocs/elearning/public/uploads
sudo chmod -R 777 /opt/lampp/htdocs/elearning/backups
```

---

### ? L?i 4: "Call to undefined function"

**Nguy?n nh?n:**
- Extensions PHP ch?a ???c b?t

**Gi?i ph?p:**
```
1. M?: php.ini
   - Windows: C:\xampp\php\php.ini
   - Linux: /opt/lampp/etc/php.ini

2. T?m v? b? d?u ; (uncomment):
   ;extension=pdo_mysql
   ? extension=pdo_mysql

   ;extension=mbstring
   ? extension=mbstring

   ;extension=gd
   ? extension=gd

3. Restart Apache
```

---

### ? L?i 5: Kh?ng hi?n th? h?nh ?nh

**Nguy?n nh?n:**
- BASE_URL kh?ng ??ng
- Th? m?c uploads thi?u file

**Gi?i ph?p:**
```php
// 1. Ki?m tra BASE_URL trong config.php
define('BASE_URL', 'http://localhost/elearning/');
// Ph?i c? d?u / ? cu?i!

// 2. T?o file ?nh m?c ??nh
// T?i ?nh placeholder v?o:
public/images/default-avatar.png
public/images/default-course.jpg
```

---

### ? L?i 6: Session kh?ng ho?t ??ng

**Nguy?n nh?n:**
- Th? m?c session kh?ng c? quy?n ghi

**Gi?i ph?p:**
```php
// Th?m v?o config.php
ini_set('session.save_path', ROOT_PATH . 'sessions');

// T?o th? m?c
mkdir sessions
chmod 777 sessions
```

---

## ?? Ho?n th?nh!

N?u t?t c? c?c b??c tr?n ??u OK, b?n ?? c?i ??t th?nh c?ng!

### ??ng nh?p v?i t?i kho?n:

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@elearning.com | admin123 |
| Teacher | teacher@elearning.com | admin123 |
| Student | student@elearning.com | admin123 |

### Ti?p theo:
1. ? ??i m?t kh?u m?c ??nh
2. ? T?o kh?a h?c m?u
3. ? Th? nghi?m t?t c? t?nh n?ng
4. ? Customize giao di?n (n?u c?n)

---

## ?? H? tr?

N?u g?p v?n ??:
- ?? Email: support@elearning.com
- ?? GitHub Issues: [Link]
- ?? Documentation: [Link]

---

**Happy Learning! ??**
