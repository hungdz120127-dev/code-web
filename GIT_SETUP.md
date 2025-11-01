# ?? Git Setup - C?u h?nh Git cho UTF-8

## ? ?? t?o c?c file:

1. **`.gitattributes`** - Force UTF-8 encoding cho t?t c? file
2. **`.editorconfig`** - ??m b?o editor d?ng UTF-8

---

## ?? C?u h?nh Git (Ch?y trong CMD/Terminal):

```bash
# Di chuy?n v?o th? m?c project
cd C:\xampp\htdocs\elearning

# C?u h?nh Git ?? x? l? UTF-8 ??ng
git config core.autocrlf false
git config core.eol lf
git config core.quotepath off

# C?u h?nh global (?p d?ng cho t?t c? repo)
git config --global core.autocrlf false
git config --global core.eol lf
git config --global core.quotepath off
```

---

## ?? Commit v? Push:

### B??c 1: Check status
```bash
git status
```

### B??c 2: Add t?t c? files
```bash
git add .
```

### B??c 3: Commit v?i message
```bash
git commit -m "Fix: UTF-8 encoding issues - Remove Vietnamese diacritics from comments

- Fix ROOT_PATH undefined error in public/index.php
- Convert all PHP files to UTF-8 without BOM
- Add .gitattributes to force UTF-8 encoding
- Add .editorconfig for consistent coding style
- Replace Vietnamese comments with non-accented version
- Add UTF-8 header to index.php"
```

### B??c 4: Push l?n GitHub
```bash
git push origin main
```

Ho?c n?u branch kh?c:
```bash
git push origin cursor/build-smart-e-learning-platform-for-schools-eb18
```

---

## ?? Ki?m tra encoding:

### Ki?m tra file encoding:
```bash
file -i config/config.php
```

K?t qu? mong ??i:
```
config/config.php: text/x-php; charset=utf-8
```

---

## ?? .gitattributes content:

File n?y ??m b?o:
- ? T?t c? file text d?ng LF (not CRLF)
- ? T?t c? file PHP d?ng UTF-8
- ? Binary files kh?ng b? convert

```
*.php text eol=lf encoding=UTF-8
*.sql text eol=lf encoding=UTF-8
*.js text eol=lf encoding=UTF-8
*.css text eol=lf encoding=UTF-8
*.md text eol=lf encoding=UTF-8
```

---

## ?? .editorconfig content:

File n?y ??m b?o editor t? ??ng:
- ? D?ng UTF-8 cho t?t c? file
- ? D?ng LF cho line endings
- ? Indent ??ng (4 spaces cho PHP)
- ? Trim trailing whitespace

```
[*.php]
charset = utf-8
indent_style = space
indent_size = 4
end_of_line = lf
```

---

## ?? Best Practices khi commit:

### 1. Lu?n check file encoding tr??c khi commit
```bash
# Notepad++: Encoding ? UTF-8 (without BOM)
# VS Code: T? ??ng UTF-8 without BOM
```

### 2. Commit message format:
```
Type: Subject line

- Bullet point 1
- Bullet point 2
```

**Types:**
- `Fix:` - S?a l?i
- `Add:` - Th?m t?nh n?ng m?i
- `Update:` - C?p nh?t t?nh n?ng c? s?n
- `Remove:` - X?a code kh?ng c?n
- `Refactor:` - T?i c?u tr?c code
- `Docs:` - C?p nh?t documentation

### 3. Tr?nh commit:
- ? File c? BOM
- ? File c? CRLF line endings
- ? File temporary
- ? File t? IDE (.idea, .vscode)
- ? File OS (.DS_Store, Thumbs.db)

---

## ?? N?u ?? commit file sai encoding:

### C?ch 1: Amend last commit
```bash
# S?a file
# Then:
git add .
git commit --amend --no-edit
git push -f origin main
```

### C?ch 2: Create new commit
```bash
# S?a file
git add .
git commit -m "Fix: Correct file encoding to UTF-8"
git push origin main
```

---

## ?? C?c file ???c ignore (xem .gitignore):

```
# IDE
.idea/
.vscode/
*.swp

# OS
.DS_Store
Thumbs.db

# Temporary
*.tmp
*.log

# Uploads (user generated content)
public/uploads/*
!public/uploads/.htaccess

# Backups
backups/*.sql
```

---

## ? Checklist tr??c khi push:

- [ ] T?t c? file PHP: UTF-8 without BOM
- [ ] T?t c? comment ti?ng Vi?t: Kh?ng d?u ho?c English
- [ ] Test local: Website ch?y OK
- [ ] Git status: Kh?ng c? file l?
- [ ] Commit message: Clear v? descriptive

---

## ?? K?t qu?:

Sau khi push l?n GitHub:
- ? Encoding ??ng UTF-8
- ? Line endings nh?t qu?n (LF)
- ? Code clean, kh?ng c? BOM
- ? Ai clone v? c?ng ch?y ???c ngay
- ? Kh?ng c?n l?i encoding

---

## ?? Commands t?m t?t:

```bash
# Setup
git config core.autocrlf false
git config core.eol lf

# Commit workflow
git status
git add .
git commit -m "Your message"
git push origin main

# Check
git log --oneline
```

---

**Done!** Code c?a b?n ?? s?n s?ng cho GitHub! ??
