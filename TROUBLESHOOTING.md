# 🔧 TROUBLESHOOTING - Xử lý lỗi chi tiết

## 🚨 Lỗi: "Not Found" - Apache/2.4.58

### ✅ GIẢI PHÁP NHANH NHẤT (100% hoạt động)

**Truy cập URL này:**
```
http://localhost/elearning/public/
```

**Hoặc:**
```
http://localhost/elearning/public/index.php
```

---

## 📋 Checklist 5 bước

### ✅ Bước 1: Kiểm tra XAMPP

Mở **XAMPP Control Panel** và đảm bảo:
- Apache: ✅ Running (màu xanh)
- MySQL: ✅ Running (màu xanh)

Nếu chưa chạy → Click **Start**

---

### ✅ Bước 2: Kiểm tra thư mục

**Đường dẫn phải đúng:**
```
C:\xampp\htdocs\elearning\
```

**Kiểm tra trong Windows Explorer:**
1. Mở `C:\xampp\htdocs\`
2. Phải thấy thư mục `elearning`
3. Vào trong thư mục `elearning`
4. Phải thấy các thư mục: `config`, `core`, `models`, `controllers`, `views`, `public`

---

### ✅ Bước 3: Import Database

**3.1. Tạo Database**

Truy cập: `http://localhost/phpmyadmin`

1. Click "New" ở sidebar trái
2. Database name: `elearning_db`
3. Collation: `utf8mb4_unicode_ci`
4. Click "Create"

**3.2. Import SQL**

1. Click vào database `elearning_db` vừa tạo
2. Click tab "Import" ở trên
3. Click "Choose File"
4. Browse đến: `C:\xampp\htdocs\elearning\config\database.sql`
5. Click "Go" ở dưới cùng
6. Đợi... (có thể mất 10-30 giây)
7. Thấy "Import has been successfully finished" → Success!

**3.3. Kiểm tra**

Click "Structure" → Phải thấy 28 tables:
- users
- courses
- lessons
- enrollments
- quiz_questions
- ... (và nhiều table khác)

---

### ✅ Bước 4: Bật mod_rewrite (Quan trọng!)

**4.1. Mở file config Apache**

File location: `C:\xampp\apache\conf\httpd.conf`

**4.2. Tìm và sửa dòng LoadModule**

Nhấn `Ctrl + F`, tìm:
```
#LoadModule rewrite_module modules/mod_rewrite.so
```

Xóa dấu `#` ở đầu thành:
```
LoadModule rewrite_module modules/mod_rewrite.so
```

**4.3. Tìm và sửa AllowOverride**

Nhấn `Ctrl + F`, tìm:
```
AllowOverride None
```

Có nhiều chỗ, đổi TẤT CẢ thành:
```
AllowOverride All
```

**4.4. Lưu file**

Nhấn `Ctrl + S` để save

**4.5. Restart Apache**

Mở XAMPP Control Panel:
1. Click **Stop** ở Apache
2. Đợi 2 giây
3. Click **Start** ở Apache
4. Đợi Apache chạy lại (màu xanh)

---

### ✅ Bước 5: Test truy cập

**Mở trình duyệt, thử các URL theo thứ tự:**

**A. Test 1:**
```
http://localhost/
```
→ Phải thấy XAMPP Dashboard

**B. Test 2:**
```
http://localhost/elearning/public/test.php
```
→ Phải thấy trang System Test với các checkmarks

**C. Test 3:**
```
http://localhost/elearning/public/
```
→ Phải thấy trang chủ E-Learning (Hero section màu xanh)

**D. Test 4 (nếu mod_rewrite OK):**
```
http://localhost/elearning/
```
→ Tự động redirect đến public/

---

## 🔑 Sau khi vào được - Đăng nhập

**URL Login:**
```
http://localhost/elearning/public/index.php?url=auth/login
```

**Hoặc click "Đăng nhập" trên navbar**

**Tài khoản test:**

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@elearning.com | admin123 |
| Teacher | teacher@elearning.com | admin123 |
| Student | student@elearning.com | admin123 |

---

## ❌ VẪN LỖI? Đọc tiếp!

### Lỗi: "config.php not found"

**Kiểm tra:**
```
C:\xampp\htdocs\elearning\config\config.php
```

File có tồn tại không? Nếu không, tạo lại với nội dung:

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
define('ALLOWED_DOC_TYPES', ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.presentationml.presentation']);

date_default_timezone_set('Asia/Ho_Chi_Minh');
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
```

---

### Lỗi: "Database connection failed"

**Kiểm tra từng bước:**

**1. MySQL có chạy không?**
- Mở XAMPP Control Panel
- MySQL phải Running

**2. Database đã tạo chưa?**
- Truy cập: http://localhost/phpmyadmin
- Tìm `elearning_db` ở sidebar trái
- Nếu không có → Tạo mới + import SQL

**3. Thông tin đăng nhập đúng chưa?**
```php
DB_HOST: localhost  ✓
DB_NAME: elearning_db  ✓
DB_USER: root  ✓
DB_PASS: ''  (để trống với XAMPP mặc định) ✓
```

**4. Test kết nối:**
Tạo file `C:\xampp\htdocs\elearning\test-db.php`:
```php
<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=elearning_db", "root", "");
    echo "✅ Database connected!";
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage();
}
```

Truy cập: `http://localhost/elearning/test-db.php`

---

### Lỗi: CSS/JS không load

**Nguyên nhân:** BASE_URL không đúng

**Giải pháp:**

Mở `config/config.php`, tìm:
```php
define('BASE_URL', 'http://localhost/elearning/');
```

**Đảm bảo:**
- Đúng tên thư mục (`elearning`)
- Có dấu `/` ở cuối
- Không có chữ `public` trong URL

**Nếu thư mục khác:**
```php
// Nếu thư mục là: C:\xampp\htdocs\myproject
define('BASE_URL', 'http://localhost/myproject/');
```

---

### Lỗi: Trang trắng (Blank Page)

**Giải pháp:**

Mở file: `public/index.php`

Thêm vào dòng ĐẦU TIÊN (sau `<?php`):
```php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

Refresh lại trang → Sẽ thấy lỗi cụ thể

---

### Lỗi: "Class not found"

**Nguyên nhân:** File không được include

**Kiểm tra:**
```
C:\xampp\htdocs\elearning\core\
├── Database.php  ✓
├── Model.php  ✓
├── Controller.php  ✓
└── Router.php  ✓
```

Tất cả file phải có đủ!

---

## 🎯 TEST SCRIPT

**Chạy ngay file test tôi đã tạo:**

```
http://localhost/elearning/public/test.php
```

File này sẽ kiểm tra:
- ✅ PHP version
- ✅ Config file
- ✅ Core files
- ✅ Database connection
- ✅ Database tables
- ✅ Upload directories

Xem kết quả để biết vấn đề ở đâu!

---

## 📊 Cấu trúc đúng

```
C:\xampp\htdocs\elearning\
├── config/
│   ├── config.php          ← Phải có!
│   └── database.sql        ← Phải có!
├── core/
│   ├── Database.php        ← Phải có!
│   ├── Model.php           ← Phải có!
│   ├── Controller.php      ← Phải có!
│   └── Router.php          ← Phải có!
├── models/                 ← 14 files
├── controllers/            ← 11 files
├── views/                  ← 14+ files
├── public/
│   ├── index.php           ← Entry point!
│   ├── test.php            ← Test script!
│   ├── .htaccess
│   ├── css/
│   ├── js/
│   └── uploads/
├── .htaccess
└── index.php
```

---

## ✅ Giải pháp từng lỗi cụ thể

### Lỗi A: "Object not found"
**URL thử:** `http://localhost/elearning/public/`

### Lỗi B: "The requested URL was not found"
**Giải pháp:** Bật mod_rewrite (xem Bước 4 ở trên)

### Lỗi C: "Access forbidden"
**Giải pháp:** Thêm vào `.htaccess`:
```apache
Options -Indexes
```

### Lỗi D: "Warning: require_once..."
**Giải pháp:** Check đường dẫn file có đúng không

### Lỗi E: "Fatal error: Class 'Database' not found"
**Giải pháp:** File `core/Database.php` thiếu hoặc không được include

---

## 🎯 URL CHÍNH XÁC để truy cập

### ✅ Option 1: Direct Access (Khuyên dùng)
```
http://localhost/elearning/public/
```

### ✅ Option 2: Test Script
```
http://localhost/elearning/public/test.php
```

### ✅ Option 3: With URL params
```
http://localhost/elearning/public/index.php?url=
```

### ✅ Option 4: With mod_rewrite
```
http://localhost/elearning/
```
(Chỉ hoạt động sau khi bật mod_rewrite)

---

## 📞 Contact nếu cần hỗ trợ

- 📧 Email: support@elearning.com
- 📖 Docs: README.md, INSTALL.md, SETUP_GUIDE.md
- 🔧 Test: Run test.php script

---

**Happy Coding! 🚀**
