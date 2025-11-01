&lt;?php require_once ROOT_PATH . 'views/layouts/header.php'; ?&gt;

<div class="container my-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="&lt;?= BASE_URL ?&gt;">Trang ch?</a></li>
            <li class="breadcrumb-item"><a href="&lt;?= BASE_URL ?&gt;course">Kh?a h?c</a></li>
            <li class="breadcrumb-item active">&lt;?= htmlspecialchars($course['title']) ?&gt;</li>
        </ol>
    </nav>
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4" data-aos="fade-up">
                <img src="&lt;?= BASE_URL . 'public/uploads/' . $course['thumbnail'] ?&gt;" 
                     class="card-img-top" style="height: 400px; object-fit: cover;"
                     onerror="this.src='https://via.placeholder.com/800x400?text=Course'">
                <div class="card-body">
                    <h2 class="card-title fw-bold">&lt;?= htmlspecialchars($course['title']) ?&gt;</h2>
                    
                    <div class="d-flex align-items-center mb-3">
                        <img src="&lt;?= BASE_URL . 'public/uploads/avatars/' . $course['teacher_avatar'] ?&gt;" 
                             class="rounded-circle me-2" width="40" height="40"
                             onerror="this.src='&lt;?= BASE_URL ?&gt;public/images/default-avatar.png'">
                        <div>
                            <small class="text-muted d-block">Gi?ng vi?n</small>
                            <strong>&lt;?= htmlspecialchars($course['teacher_name']) ?&gt;</strong>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <span class="badge bg-primary me-2">&lt;?= ucfirst($course['level']) ?&gt;</span>
                        <span class="badge bg-info me-2">
                            <i class="fas fa-user"></i> &lt;?= $studentCount ?&gt; h?c vi?n
                        </span>
                        <span class="badge bg-warning">
                            <i class="fas fa-star"></i> &lt;?= number_format($ratingData['avg_rating'] ?? 0, 1) ?&gt;
                        </span>
                    </div>
                    
                    <hr>
                    
                    <h5>M? t? kh?a h?c</h5>
                    <p class="text-muted">&lt;?= nl2br(htmlspecialchars($course['description'])) ?&gt;</p>
                    
                    <hr>
                    
                    <h5>N?i dung kh?a h?c</h5>
                    <div class="accordion" id="courseContent">
                        &lt;?php foreach ($chapters as $index => $chapter): ?&gt;
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button &lt;?= $index > 0 ? 'collapsed' : '' ?&gt;" 
                                        type="button" data-bs-toggle="collapse" 
                                        data-bs-target="#chapter&lt;?= $chapter['id'] ?&gt;">
                                    <strong>Ch??ng &lt;?= $index + 1 ?&gt;:</strong>&nbsp;&lt;?= htmlspecialchars($chapter['title']) ?&gt;
                                    <span class="badge bg-secondary ms-2">&lt;?= count($chapter['lessons']) ?&gt; b?i</span>
                                </button>
                            </h2>
                            <div id="chapter&lt;?= $chapter['id'] ?&gt;" 
                                 class="accordion-collapse collapse &lt;?= $index == 0 ? 'show' : '' ?&gt;" 
                                 data-bs-parent="#courseContent">
                                <div class="accordion-body">
                                    <ul class="list-group list-group-flush">
                                        &lt;?php foreach ($chapter['lessons'] as $lesson): ?&gt;
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span>
                                                <i class="fas fa-play-circle text-primary"></i>
                                                &lt;?= htmlspecialchars($lesson['title']) ?&gt;
                                            </span>
                                            &lt;?php if ($lesson['duration'] > 0): ?&gt;
                                            <small class="text-muted">&lt;?= $lesson['duration'] ?&gt; ph?t</small>
                                            &lt;?php endif; ?&gt;
                                        </li>
                                        &lt;?php endforeach; ?&gt;
                                    </ul>
                                </div>
                            </div>
                        </div>
                        &lt;?php endforeach; ?&gt;
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm sticky-top" style="top: 100px;" data-aos="fade-up">
                <div class="card-body">
                    &lt;?php if ($isEnrolled): ?&gt;
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i> B?n ?? ??ng k? kh?a h?c n?y
                        </div>
                        <div class="mb-3">
                            <small class="text-muted">Ti?n ?? h?c t?p</small>
                            <div class="progress mt-2" style="height: 20px;">
                                <div class="progress-bar" style="width: &lt;?= $enrollment['progress'] ?&gt;%">
                                    &lt;?= number_format($enrollment['progress'], 1) ?&gt;%
                                </div>
                            </div>
                        </div>
                        <a href="&lt;?= BASE_URL ?&gt;course/learn/&lt;?= $course['id'] ?&gt;" 
                           class="btn btn-primary w-100 btn-lg">
                            <i class="fas fa-play"></i> Ti?p t?c h?c
                        </a>
                    &lt;?php else: ?&gt;
                        &lt;?php if (isset($user)): ?&gt;
                            <h4 class="text-center mb-3">
                                &lt;?php if ($course['price'] > 0): ?&gt;
                                    &lt;?= number_format($course['price'], 0, ',', '.') ?&gt; VN?
                                &lt;?php else: ?&gt;
                                    <span class="text-success">Mi?n ph?</span>
                                &lt;?php endif; ?&gt;
                            </h4>
                            <a href="&lt;?= BASE_URL ?&gt;course/enroll/&lt;?= $course['id'] ?&gt;" 
                               class="btn btn-success w-100 btn-lg mb-3">
                                <i class="fas fa-user-plus"></i> ??ng k? kh?a h?c
                            </a>
                        &lt;?php else: ?&gt;
                            <div class="alert alert-info">
                                Vui l?ng ??ng nh?p ?? ??ng k? kh?a h?c
                            </div>
                            <a href="&lt;?= BASE_URL ?&gt;auth/login" class="btn btn-primary w-100">
                                ??ng nh?p
                            </a>
                        &lt;?php endif; ?&gt;
                    &lt;?php endif; ?&gt;
                    
                    <hr>
                    
                    <h6>Th?ng tin kh?a h?c</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="fas fa-book text-primary"></i>
                            <strong>&lt;?= count($chapters) ?&gt;</strong> ch??ng
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-play-circle text-primary"></i>
                            <strong>&lt;?= array_sum(array_map(function($c) { return count($c['lessons']); }, $chapters)) ?&gt;</strong> b?i h?c
                        </li>
                        &lt;?php if ($course['duration'] > 0): ?&gt;
                        <li class="mb-2">
                            <i class="fas fa-clock text-primary"></i>
                            <strong>&lt;?= $course['duration'] ?&gt;</strong> ph?t
                        </li>
                        &lt;?php endif; ?&gt;
                        <li class="mb-2">
                            <i class="fas fa-signal text-primary"></i>
                            C?p ??: <strong>&lt;?= ucfirst($course['level']) ?&gt;</strong>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-certificate text-primary"></i>
                            C? ch?ng ch?
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

&lt;?php require_once ROOT_PATH . 'views/layouts/footer.php'; ?&gt;
