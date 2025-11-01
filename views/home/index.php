&lt;?php require_once ROOT_PATH . 'views/layouts/header.php'; ?&gt;

<!-- Hero Section -->
<section class="hero-section bg-gradient text-white py-5" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <div class="container py-5">
        <div class="row align-items-center">
            <div class="col-lg-6" data-aos="fade-right">
                <h1 class="display-4 fw-bold mb-4">H?c t?p th?ng minh<br>T??ng lai r?c r?</h1>
                <p class="lead mb-4">Kh?m ph? h?ng tr?m kh?a h?c ch?t l??ng cao, h?c m?i l?c m?i n?i v?i n?n t?ng E-Learning hi?n ??i nh?t.</p>
                <div class="d-flex gap-3">
                    <a href="&lt;?= BASE_URL ?&gt;course" class="btn btn-light btn-lg">
                        <i class="fas fa-book"></i> Kh?m ph? kh?a h?c
                    </a>
                    &lt;?php if (!isset($user)): ?&gt;
                    <a href="&lt;?= BASE_URL ?&gt;auth/register" class="btn btn-outline-light btn-lg">
                        ??ng k? ngay
                    </a>
                    &lt;?php endif; ?&gt;
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <img src="&lt;?= BASE_URL ?&gt;public/images/hero-illustration.png" 
                     alt="E-Learning" class="img-fluid"
                     onerror="this.src='https://via.placeholder.com/600x400?text=E-Learning+Platform'">
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-5">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-lg-12">
                <h2 class="display-6 fw-bold" data-aos="fade-up">T?i sao ch?n ch?ng t?i?</h2>
                <p class="text-muted" data-aos="fade-up" data-aos-delay="100">N?n t?ng h?c t?p v?i nhi?u t?nh n?ng v??t tr?i</p>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-md-4" data-aos="fade-up">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="fas fa-video fa-2x"></i>
                        </div>
                        <h5 class="card-title">Video ch?t l??ng cao</h5>
                        <p class="card-text text-muted">H?c v?i video HD, ?m thanh r? r?ng, d? hi?u</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="fas fa-certificate fa-2x"></i>
                        </div>
                        <h5 class="card-title">Ch?ng ch? ho?n th?nh</h5>
                        <p class="card-text text-muted">Nh?n ch?ng ch? sau khi ho?n th?nh kh?a h?c</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon bg-warning text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="fas fa-trophy fa-2x"></i>
                        </div>
                        <h5 class="card-title">Gamification</h5>
                        <p class="card-text text-muted">H? th?ng XP, huy hi?u, b?ng x?p h?ng</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon bg-info text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="fas fa-comments fa-2x"></i>
                        </div>
                        <h5 class="card-title">Di?n ??n t??ng t?c</h5>
                        <p class="card-text text-muted">H?i ??p, th?o lu?n v?i gi?o vi?n v? b?n h?c</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon bg-danger text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="fas fa-mobile-alt fa-2x"></i>
                        </div>
                        <h5 class="card-title">Responsive Design</h5>
                        <p class="card-text text-muted">H?c m?i l?c m?i n?i tr?n m?i thi?t b?</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon bg-secondary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="fas fa-clock fa-2x"></i>
                        </div>
                        <h5 class="card-title">H?c theo ti?n ??</h5>
                        <p class="card-text text-muted">Theo d?i ti?n ?? h?c t?p c?a b?n</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Popular Courses -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row mb-4">
            <div class="col-lg-12">
                <h2 class="display-6 fw-bold" data-aos="fade-up">Kh?a h?c ph? bi?n</h2>
                <p class="text-muted" data-aos="fade-up">Nh?ng kh?a h?c ???c y?u th?ch nh?t</p>
            </div>
        </div>
        <div class="row g-4">
            &lt;?php foreach ($popularCourses as $course): ?&gt;
            <div class="col-md-6 col-lg-4" data-aos="fade-up">
                <div class="card h-100 border-0 shadow-sm course-card">
                    <img src="&lt;?= BASE_URL . 'public/uploads/' . $course['thumbnail'] ?&gt;" 
                         class="card-img-top" alt="&lt;?= htmlspecialchars($course['title']) ?&gt;"
                         style="height: 200px; object-fit: cover;"
                         onerror="this.src='https://via.placeholder.com/400x200?text=Course'">
                    <div class="card-body">
                        <h5 class="card-title">&lt;?= htmlspecialchars($course['title']) ?&gt;</h5>
                        <p class="card-text text-muted small">&lt;?= substr(htmlspecialchars($course['description']), 0, 100) ?&gt;...</p>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <small class="text-muted">
                                <i class="fas fa-user"></i> &lt;?= htmlspecialchars($course['teacher_name']) ?&gt;
                            </small>
                            <small class="text-muted">
                                <i class="fas fa-eye"></i> &lt;?= $course['view_count'] ?&gt;
                            </small>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-0">
                        <a href="&lt;?= BASE_URL ?&gt;course/view/&lt;?= $course['id'] ?&gt;" class="btn btn-primary w-100">
                            Xem kh?a h?c
                        </a>
                    </div>
                </div>
            </div>
            &lt;?php endforeach; ?&gt;
        </div>
        <div class="text-center mt-4">
            <a href="&lt;?= BASE_URL ?&gt;course" class="btn btn-outline-primary btn-lg">
                Xem t?t c? kh?a h?c <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-5">
    <div class="container">
        <div class="row text-center g-4">
            <div class="col-md-3" data-aos="fade-up">
                <div class="stat-item">
                    <h2 class="display-4 fw-bold text-primary">500+</h2>
                    <p class="text-muted">Kh?a h?c</p>
                </div>
            </div>
            <div class="col-md-3" data-aos="fade-up" data-aos-delay="100">
                <div class="stat-item">
                    <h2 class="display-4 fw-bold text-success">10K+</h2>
                    <p class="text-muted">H?c vi?n</p>
                </div>
            </div>
            <div class="col-md-3" data-aos="fade-up" data-aos-delay="200">
                <div class="stat-item">
                    <h2 class="display-4 fw-bold text-warning">100+</h2>
                    <p class="text-muted">Gi?o vi?n</p>
                </div>
            </div>
            <div class="col-md-3" data-aos="fade-up" data-aos-delay="300">
                <div class="stat-item">
                    <h2 class="display-4 fw-bold text-info">98%</h2>
                    <p class="text-muted">H?i l?ng</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5 bg-primary text-white">
    <div class="container text-center">
        <h2 class="display-5 fw-bold mb-4" data-aos="fade-up">S?n s?ng b?t ??u h?nh tr?nh h?c t?p?</h2>
        <p class="lead mb-4" data-aos="fade-up">??ng k? ngay h?m nay v? nh?n mi?n ph? kh?a h?c ??u ti?n!</p>
        &lt;?php if (!isset($user)): ?&gt;
        <a href="&lt;?= BASE_URL ?&gt;auth/register" class="btn btn-light btn-lg" data-aos="fade-up">
            <i class="fas fa-rocket"></i> ??ng k? mi?n ph?
        </a>
        &lt;?php else: ?&gt;
        <a href="&lt;?= BASE_URL ?&gt;course" class="btn btn-light btn-lg" data-aos="fade-up">
            <i class="fas fa-book"></i> Kh?m ph? kh?a h?c
        </a>
        &lt;?php endif; ?&gt;
    </div>
</section>

&lt;?php require_once ROOT_PATH . 'views/layouts/footer.php'; ?&gt;
