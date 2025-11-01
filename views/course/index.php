&lt;?php require_once ROOT_PATH . 'views/layouts/header.php'; ?&gt;

<div class="container my-4">
    <div class="row mb-4">
        <div class="col-lg-8">
            <h2 class="fw-bold"><i class="fas fa-book"></i> Kh?a h?c</h2>
            <p class="text-muted">Kh?m ph? h?ng tr?m kh?a h?c ch?t l??ng cao</p>
        </div>
        <div class="col-lg-4">
            <form action="&lt;?= BASE_URL ?&gt;course/search" method="GET" class="d-flex">
                <input type="text" name="q" class="form-control me-2" placeholder="T?m ki?m kh?a h?c...">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
    </div>
    
    <div class="row g-4">
        &lt;?php if (empty($courses)): ?&gt;
            <div class="col-12 text-center py-5">
                <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                <h5 class="text-muted">Ch?a c? kh?a h?c n?o</h5>
            </div>
        &lt;?php else: ?&gt;
            &lt;?php foreach ($courses as $course): ?&gt;
            <div class="col-md-6 col-lg-4" data-aos="fade-up">
                <div class="card h-100 border-0 shadow-sm course-card">
                    <img src="&lt;?= BASE_URL . 'public/uploads/' . $course['thumbnail'] ?&gt;" 
                         class="card-img-top" alt="&lt;?= htmlspecialchars($course['title']) ?&gt;"
                         style="height: 200px; object-fit: cover;"
                         onerror="this.src='https://via.placeholder.com/400x200?text=Course'">
                    <div class="card-body">
                        <h5 class="card-title">&lt;?= htmlspecialchars($course['title']) ?&gt;</h5>
                        <p class="card-text text-muted small">
                            &lt;?= substr(htmlspecialchars($course['description']), 0, 120) ?&gt;...
                        </p>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <small class="text-muted">
                                <i class="fas fa-user"></i> &lt;?= htmlspecialchars($course['teacher_name']) ?&gt;
                            </small>
                            <span class="badge bg-primary">&lt;?= ucfirst($course['level']) ?&gt;</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="fas fa-eye"></i> &lt;?= $course['view_count'] ?&gt; l??t xem
                            </small>
                            &lt;?php if ($course['rating'] > 0): ?&gt;
                            <small class="text-warning">
                                <i class="fas fa-star"></i> &lt;?= number_format($course['rating'], 1) ?&gt;
                            </small>
                            &lt;?php endif; ?&gt;
                        </div>
                    </div>
                    <div class="card-footer bg-white border-0">
                        <a href="&lt;?= BASE_URL ?&gt;course/view/&lt;?= $course['id'] ?&gt;" class="btn btn-primary w-100">
                            <i class="fas fa-arrow-right"></i> Xem chi ti?t
                        </a>
                    </div>
                </div>
            </div>
            &lt;?php endforeach; ?&gt;
        &lt;?php endif; ?&gt;
    </div>
</div>

&lt;?php require_once ROOT_PATH . 'views/layouts/footer.php'; ?&gt;
