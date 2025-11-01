&lt;?php require_once ROOT_PATH . 'views/layouts/header.php'; ?&gt;

<div class="container my-4">
    <div class="row">
        <div class="col-lg-12 mb-4">
            <h2 class="fw-bold">
                <i class="fas fa-tachometer-alt"></i> Dashboard - H?c vi?n
            </h2>
            <p class="text-muted">Ch?o m?ng tr? l?i, &lt;?= htmlspecialchars($user['name']) ?&gt;!</p>
        </div>
    </div>
    
    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm" data-aos="fade-up">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Kh?a h?c</h6>
                            <h3 class="mb-0 fw-bold">&lt;?= $stats['enrolled_courses'] ?&gt;</h3>
                        </div>
                        <div class="bg-primary text-white rounded-circle p-3">
                            <i class="fas fa-book fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm" data-aos="fade-up" data-aos-delay="100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Ho?n th?nh</h6>
                            <h3 class="mb-0 fw-bold">&lt;?= $stats['completed_courses'] ?&gt;</h3>
                        </div>
                        <div class="bg-success text-white rounded-circle p-3">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm" data-aos="fade-up" data-aos-delay="200">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">XP</h6>
                            <h3 class="mb-0 fw-bold">&lt;?= $user['xp'] ?&gt;</h3>
                        </div>
                        <div class="bg-warning text-white rounded-circle p-3">
                            <i class="fas fa-star fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm" data-aos="fade-up" data-aos-delay="300">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Level</h6>
                            <h3 class="mb-0 fw-bold">&lt;?= $user['level'] ?&gt;</h3>
                        </div>
                        <div class="bg-info text-white rounded-circle p-3">
                            <i class="fas fa-trophy fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <!-- Enrolled Courses -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4" data-aos="fade-up">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-book-open"></i> Kh?a h?c c?a t?i</h5>
                </div>
                <div class="card-body">
                    &lt;?php if (empty($enrolledCourses)): ?&gt;
                        <div class="text-center py-5">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted">B?n ch?a ??ng k? kh?a h?c n?o</p>
                            <a href="&lt;?= BASE_URL ?&gt;course" class="btn btn-primary">Kh?m ph? kh?a h?c</a>
                        </div>
                    &lt;?php else: ?&gt;
                        &lt;?php foreach ($enrolledCourses as $course): ?&gt;
                        <div class="d-flex align-items-center mb-3 p-3 border rounded">
                            <img src="&lt;?= BASE_URL . 'public/uploads/' . $course['thumbnail'] ?&gt;" 
                                 class="rounded me-3" width="80" height="80" style="object-fit: cover;"
                                 onerror="this.src='https://via.placeholder.com/80'">
                            <div class="flex-grow-1">
                                <h6 class="mb-1">&lt;?= htmlspecialchars($course['title']) ?&gt;</h6>
                                <small class="text-muted">
                                    <i class="fas fa-user"></i> &lt;?= htmlspecialchars($course['teacher_name']) ?&gt;
                                </small>
                                <div class="progress mt-2" style="height: 8px;">
                                    <div class="progress-bar" role="progressbar" 
                                         style="width: &lt;?= $course['progress'] ?&gt;%"
                                         aria-valuenow="&lt;?= $course['progress'] ?&gt;" aria-valuemin="0" aria-valuemax="100">
                                    </div>
                                </div>
                                <small class="text-muted">&lt;?= number_format($course['progress'], 1) ?&gt;% ho?n th?nh</small>
                            </div>
                            <a href="&lt;?= BASE_URL ?&gt;course/learn/&lt;?= $course['course_id'] ?&gt;" class="btn btn-primary btn-sm">
                                <i class="fas fa-play"></i> Ti?p t?c
                            </a>
                        </div>
                        &lt;?php endforeach; ?&gt;
                    &lt;?php endif; ?&gt;
                </div>
            </div>
            
            <!-- Recommended Courses -->
            <div class="card border-0 shadow-sm" data-aos="fade-up">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-lightbulb"></i> Kh?a h?c ?? xu?t</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        &lt;?php foreach ($recommendedCourses as $course): ?&gt;
                        <div class="col-md-6">
                            <div class="card h-100">
                                <img src="&lt;?= BASE_URL . 'public/uploads/' . $course['thumbnail'] ?&gt;" 
                                     class="card-img-top" style="height: 150px; object-fit: cover;"
                                     onerror="this.src='https://via.placeholder.com/300x150'">
                                <div class="card-body">
                                    <h6 class="card-title">&lt;?= htmlspecialchars($course['title']) ?&gt;</h6>
                                    <small class="text-muted">
                                        <i class="fas fa-user"></i> &lt;?= htmlspecialchars($course['teacher_name']) ?&gt;
                                    </small>
                                </div>
                                <div class="card-footer bg-white">
                                    <a href="&lt;?= BASE_URL ?&gt;course/view/&lt;?= $course['id'] ?&gt;" class="btn btn-sm btn-outline-primary w-100">
                                        Xem chi ti?t
                                    </a>
                                </div>
                            </div>
                        </div>
                        &lt;?php endforeach; ?&gt;
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- User Info -->
            <div class="card border-0 shadow-sm mb-4" data-aos="fade-up">
                <div class="card-body text-center">
                    <img src="&lt;?= BASE_URL . 'public/uploads/avatars/' . $user['avatar'] ?&gt;" 
                         class="rounded-circle mb-3" width="100" height="100" alt="Avatar"
                         onerror="this.src='&lt;?= BASE_URL ?&gt;public/images/default-avatar.png'">
                    <h5 class="mb-1">&lt;?= htmlspecialchars($user['name']) ?&gt;</h5>
                    <p class="text-muted mb-2">&lt;?= htmlspecialchars($user['email']) ?&gt;</p>
                    <div class="badge bg-primary mb-3">Level &lt;?= $user['level'] ?&gt;</div>
                    <div class="progress mb-2" style="height: 8px;">
                        &lt;?php 
                        $nextLevelXP = pow($user['level'], 2) * 100;
                        $currentLevelXP = pow($user['level'] - 1, 2) * 100;
                        $progressXP = $user['xp'] - $currentLevelXP;
                        $requiredXP = $nextLevelXP - $currentLevelXP;
                        $percentage = ($progressXP / $requiredXP) * 100;
                        ?&gt;
                        <div class="progress-bar" style="width: &lt;?= $percentage ?&gt;%"></div>
                    </div>
                    <small class="text-muted">&lt;?= $user['xp'] ?&gt; / &lt;?= $nextLevelXP ?&gt; XP</small>
                </div>
            </div>
            
            <!-- Notifications -->
            <div class="card border-0 shadow-sm" data-aos="fade-up">
                <div class="card-header bg-white">
                    <h6 class="mb-0"><i class="fas fa-bell"></i> Th?ng b?o</h6>
                </div>
                <div class="card-body p-0">
                    &lt;?php if (empty($notifications)): ?&gt;
                        <div class="text-center py-3">
                            <small class="text-muted">Kh?ng c? th?ng b?o m?i</small>
                        </div>
                    &lt;?php else: ?&gt;
                        <div class="list-group list-group-flush">
                            &lt;?php foreach ($notifications as $notification): ?&gt;
                            <a href="&lt;?= $notification['link'] ? BASE_URL . $notification['link'] : '#' ?&gt;" 
                               class="list-group-item list-group-item-action &lt;?= !$notification['is_read'] ? 'bg-light' : '' ?&gt;">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1 small">&lt;?= htmlspecialchars($notification['title']) ?&gt;</h6>
                                    <small>&lt;?= date('H:i', strtotime($notification['created_at'])) ?&gt;</small>
                                </div>
                                <p class="mb-0 small text-muted">&lt;?= htmlspecialchars($notification['message']) ?&gt;</p>
                            </a>
                            &lt;?php endforeach; ?&gt;
                        </div>
                    &lt;?php endif; ?&gt;
                </div>
            </div>
        </div>
    </div>
</div>

&lt;?php require_once ROOT_PATH . 'views/layouts/footer.php'; ?&gt;
