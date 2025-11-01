&lt;?php require_once ROOT_PATH . 'views/layouts/header.php'; ?&gt;

<div class="container my-4">
    <div class="row mb-4">
        <div class="col-lg-12">
            <h2 class="fw-bold"><i class="fas fa-chalkboard-teacher"></i> Dashboard - Gi?o vi?n</h2>
            <p class="text-muted">Qu?n l? kh?a h?c v? h?c vi?n c?a b?n</p>
        </div>
    </div>
    
    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm" data-aos="fade-up">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Kh?a h?c</h6>
                            <h3 class="mb-0 fw-bold">&lt;?= $stats['total_courses'] ?&gt;</h3>
                        </div>
                        <div class="bg-primary text-white rounded-circle p-3">
                            <i class="fas fa-book fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm" data-aos="fade-up" data-aos-delay="100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">H?c vi?n</h6>
                            <h3 class="mb-0 fw-bold">&lt;?= $stats['total_students'] ?&gt;</h3>
                        </div>
                        <div class="bg-success text-white rounded-circle p-3">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm" data-aos="fade-up" data-aos-delay="200">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">L??t xem</h6>
                            <h3 class="mb-0 fw-bold">&lt;?= $stats['total_views'] ?&gt;</h3>
                        </div>
                        <div class="bg-info text-white rounded-circle p-3">
                            <i class="fas fa-eye fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Courses -->
    <div class="card border-0 shadow-sm" data-aos="fade-up">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-book-open"></i> Kh?a h?c c?a t?i</h5>
            <button class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> T?o kh?a h?c m?i
            </button>
        </div>
        <div class="card-body">
            &lt;?php if (empty($courses)): ?&gt;
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <p class="text-muted">B?n ch?a t?o kh?a h?c n?o</p>
                    <button class="btn btn-primary">T?o kh?a h?c ??u ti?n</button>
                </div>
            &lt;?php else: ?&gt;
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Kh?a h?c</th>
                                <th>Tr?ng th?i</th>
                                <th>H?c vi?n</th>
                                <th>L??t xem</th>
                                <th>Ng?y t?o</th>
                                <th>Thao t?c</th>
                            </tr>
                        </thead>
                        <tbody>
                            &lt;?php foreach ($courses as $course): ?&gt;
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="&lt;?= BASE_URL . 'public/uploads/' . $course['thumbnail'] ?&gt;" 
                                             class="rounded me-2" width="50" height="50" style="object-fit: cover;"
                                             onerror="this.src='https://via.placeholder.com/50'">
                                        <div>
                                            <h6 class="mb-0">&lt;?= htmlspecialchars($course['title']) ?&gt;</h6>
                                            <small class="text-muted">&lt;?= $course['category'] ?&gt;</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    &lt;?php if ($course['is_published']): ?&gt;
                                        <span class="badge bg-success">?? xu?t b?n</span>
                                    &lt;?php else: ?&gt;
                                        <span class="badge bg-warning">Nh?p</span>
                                    &lt;?php endif; ?&gt;
                                </td>
                                <td>
                                    &lt;?php
                                    require_once ROOT_PATH . 'models/Course.php';
                                    $courseModel = new Course();
                                    $studentCount = $courseModel->getStudentCount($course['id']);
                                    ?&gt;
                                    &lt;?= $studentCount ?&gt;
                                </td>
                                <td>&lt;?= $course['view_count'] ?&gt;</td>
                                <td>&lt;?= date('d/m/Y', strtotime($course['created_at'])) ?&gt;</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="&lt;?= BASE_URL ?&gt;course/view/&lt;?= $course['id'] ?&gt;" class="btn btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button class="btn btn-outline-secondary">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-outline-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            &lt;?php endforeach; ?&gt;
                        </tbody>
                    </table>
                </div>
            &lt;?php endif; ?&gt;
        </div>
    </div>
</div>

&lt;?php require_once ROOT_PATH . 'views/layouts/footer.php'; ?&gt;
