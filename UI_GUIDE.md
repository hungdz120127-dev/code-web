# ?? UI/UX Design Guide - E-Learning Platform

## ?? Color Scheme

### Primary Colors
```css
--primary: #4361ee       /* Xanh d??ng ch? ??o */
--primary-dark: #3730a3  /* Xanh d??ng ??m (hover) */
--primary-light: #818cf8 /* Xanh d??ng nh?t */
--secondary: #3b82f6     /* Xanh d??ng ph? */
```

### Accent Colors
```css
--accent: #fbbf24        /* V?ng nh?t (nh?n) */
--accent-light: #fde68a  /* V?ng r?t nh?t */
```

### Status Colors
```css
--success: #10b981       /* Xanh l? - Success */
--danger: #ef4444        /* ?? - Error/Danger */
--warning: #f59e0b       /* Cam - Warning */
--info: #06b6d4          /* Xanh ng?c - Info */
```

### Neutral Colors
```css
--white: #ffffff
--light: #f8fafc
--light-gray: #e2e8f0
--gray: #94a3b8
--dark-gray: #475569
--dark: #1e293b
```

---

## ?? Typography

### Fonts
- **Primary**: Poppins (Google Fonts)
- **Alternative**: Inter, Nunito Sans
- **Fallback**: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif

### Font Sizes
```css
Base: 16px
Small: 0.875rem (14px)
Large: 1.125rem (18px)
XL: 1.25rem (20px)
```

### Font Weights
```css
Normal: 400
Medium: 500
Semibold: 600
Bold: 700
```

### Usage
```html
<h1>Heading 1 - 2.5rem (40px)</h1>
<h2>Heading 2 - 2rem (32px)</h2>
<h3>Heading 3 - 1.75rem (28px)</h3>
<h4>Heading 4 - 1.5rem (24px)</h4>
<h5>Heading 5 - 1.25rem (20px)</h5>
<h6>Heading 6 - 1rem (16px)</h6>
<p>Paragraph - 1rem (16px)</p>
```

---

## ?? Cards

### Standard Card
```html
<div class="card">
    <div class="card-header">
        <h5>Card Title</h5>
    </div>
    <div class="card-body">
        <p>Card content...</p>
    </div>
    <div class="card-footer">
        <button class="btn btn-primary">Action</button>
    </div>
</div>
```

### Course Card
```html
<div class="card course-card">
    <img src="..." class="card-img-top">
    <div class="card-body">
        <h5 class="card-title">Course Title</h5>
        <p class="card-text">Description...</p>
        <div class="d-flex justify-content-between">
            <span class="badge badge-primary">Level</span>
            <span class="text-muted">
                <i class="fas fa-eye"></i> 100 views
            </span>
        </div>
    </div>
    <div class="card-footer">
        <a href="#" class="btn btn-primary w-100">Xem kh?a h?c</a>
    </div>
</div>
```

### Features
- **Border Radius**: 0.75rem (12px)
- **Shadow**: Soft shadow on hover
- **Hover Effect**: Lift up (-8px translateY)
- **Top Border**: 4px gradient bar on hover

---

## ?? Buttons

### Types
```html
<!-- Primary Button -->
<button class="btn btn-primary">
    <i class="fas fa-check"></i> Primary
</button>

<!-- Success Button -->
<button class="btn btn-success">
    <i class="fas fa-save"></i> Success
</button>

<!-- Outline Button -->
<button class="btn btn-outline-primary">
    <i class="fas fa-arrow-right"></i> Outline
</button>

<!-- Sizes -->
<button class="btn btn-primary btn-sm">Small</button>
<button class="btn btn-primary">Normal</button>
<button class="btn btn-primary btn-lg">Large</button>
```

### Features
- **Border Radius**: 0.5rem (8px)
- **Padding**: 0.625rem 1.5rem
- **Font Weight**: 600
- **Shadow**: Colorful shadow matching button color
- **Hover**: Lift up (-3px) with enhanced shadow
- **Icons**: Built-in support with proper spacing

---

## ?? Progress Bar

### Basic Progress
```html
<div class="progress">
    <div class="progress-bar" style="width: 75%">75%</div>
</div>
```

### Features
- **Height**: 0.75rem (12px)
- **Border Radius**: 1rem (16px)
- **Gradient**: Primary to Accent color
- **Animation**: Shimmer effect
- **Smooth**: 0.8s cubic-bezier transition

---

## ??? Badges

### Types
```html
<span class="badge badge-primary">Primary</span>
<span class="badge badge-success">Success</span>
<span class="badge badge-warning">Warning</span>
<span class="badge badge-info">Info</span>
```

### Features
- **Border Radius**: 0.5rem (8px)
- **Padding**: 0.375rem 0.875rem
- **Font Weight**: 600
- **Icons**: Support for icons inside

---

## ?? Navbar

### Structure
```html
<nav class="navbar navbar-expand-lg navbar-light sticky-top">
    <div class="container">
        <a class="navbar-brand" href="#">
            <i class="fas fa-graduation-cap"></i>
            <span>E-Learning</span>
        </a>
        <button class="navbar-toggler">...</button>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#">Home</a>
                </li>
                ...
            </ul>
        </div>
    </div>
</nav>
```

### Features
- **Background**: White with blur effect
- **Shadow**: Subtle box-shadow
- **Sticky**: Fixed on scroll
- **Responsive**: Hamburger menu on mobile
- **Brand**: Primary color with icon
- **Links**: Rounded on hover with background

---

## ?? Responsive Design

### Breakpoints
```css
/* Large Desktop */
@media (min-width: 1200px) { ... }

/* Desktop */
@media (min-width: 992px) { ... }

/* Tablet */
@media (max-width: 991px) { ... }

/* Mobile */
@media (max-width: 768px) { ... }

/* Small Mobile */
@media (max-width: 576px) { ... }
```

### Mobile Optimizations
1. **Font Size**: Auto-adjusts (14-16px)
2. **Spacing**: Reduced on mobile
3. **Buttons**: Full width on mobile
4. **Cards**: Reduced padding
5. **Animations**: Lighter/faster on mobile
6. **Navigation**: Hamburger menu

---

## ? Animations

### AOS (Animate On Scroll)
```html
<!-- Fade Up -->
<div data-aos="fade-up">Content</div>

<!-- Fade Left -->
<div data-aos="fade-left">Content</div>

<!-- With Delay -->
<div data-aos="fade-up" data-aos-delay="100">Content</div>
```

### Custom Animations
```css
/* Hover Lift */
.hover-lift:hover {
    transform: translateY(-8px);
}

/* Hover Scale */
.hover-scale:hover {
    transform: scale(1.05);
}

/* Hover Glow */
.hover-glow:hover {
    box-shadow: 0 0 20px rgba(67, 97, 238, 0.4);
}
```

### Mobile Animation Rules
- Duration: Max 300ms on mobile
- Transform: Reduced scale/translate
- Heavy effects: Disabled on mobile

---

## ?? Dark Mode

### Toggle Implementation
```javascript
// Automatic theme detection
const savedTheme = localStorage.getItem('theme') || 'light';
body.setAttribute('data-theme', savedTheme);
```

### Dark Mode Colors
```css
[data-theme="dark"] {
    --bg-color: #0f172a;
    --text-color: #f1f5f9;
    --card-bg: #1e293b;
    --border-color: #334155;
}
```

---

## ?? Best Practices

### 1. Spacing
- Use consistent spacing variables
- Maintain visual hierarchy
- Add breathing room around elements

### 2. Colors
- Stick to the color scheme
- Use accent color sparingly
- Ensure sufficient contrast

### 3. Typography
- Use Poppins for all text
- Maintain readable line-height
- Limit font sizes to defined scale

### 4. Buttons
- Always include icons for clarity
- Use proper colors for actions
- Add loading states

### 5. Cards
- Consistent padding
- Proper shadow on hover
- Include all relevant info

### 6. Responsive
- Test on multiple devices
- Ensure touch-friendly UI
- Optimize for mobile performance

### 7. Accessibility
- Proper contrast ratios
- Keyboard navigation
- Screen reader support
- ARIA labels

---

## ?? Component Examples

### Dashboard Card with Icon
```html
<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h6 class="text-muted mb-1">Kh?a h?c</h6>
                <h3 class="mb-0 fw-bold">25</h3>
            </div>
            <div class="bg-primary text-white rounded-circle p-3">
                <i class="fas fa-book fa-2x"></i>
            </div>
        </div>
    </div>
</div>
```

### Progress Card
```html
<div class="card">
    <div class="card-body">
        <h6>Course Title</h6>
        <div class="progress mt-2">
            <div class="progress-bar" style="width: 65%">65%</div>
        </div>
        <small class="text-muted">13/20 b?i ?? ho?n th?nh</small>
    </div>
</div>
```

---

## ??? Customization

### Edit Colors
File: `public/css/custom.css`
```css
:root {
    --custom-primary: #4361ee;     /* Change this */
    --custom-accent: #fbbf24;      /* Change this */
}
```

### Edit Font
File: `views/layouts/header.php`
```html
<!-- Change font in Google Fonts link -->
<link href="https://fonts.googleapis.com/css2?family=YourFont:wght@...">
```

Then update in `custom.css`:
```css
:root {
    --font-main: 'YourFont', sans-serif;
}
```

---

## ?? Color Palette Reference

### Light Mode
```
Background:     #ffffff
Secondary BG:   #f8fafc
Text:           #1e293b
Text Secondary: #64748b
Border:         #e2e8f0
```

### Dark Mode
```
Background:     #0f172a
Secondary BG:   #1e293b
Text:           #f1f5f9
Text Secondary: #cbd5e1
Border:         #334155
```

---

**Happy Designing! ???**
