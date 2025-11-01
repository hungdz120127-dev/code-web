&lt;?php require_once ROOT_PATH . 'views/layouts/header.php'; ?&gt;

<div class="container my-4">
    <div class="row">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4" data-aos="fade-up">
                <div class="card-body text-center">
                    <img src="&lt;?= BASE_URL . 'public/uploads/avatars/' . $user['avatar'] ?&gt;" 
                         class="rounded-circle mb-3" width="150" height="150" 
                         id="avatar-preview"
                         onerror="this.src='&lt;?= BASE_URL ?&gt;public/images/default-avatar.png'">
                    <h4 class="mb-1">&lt;?= htmlspecialchars($user['name']) ?&gt;</h4>
                    <p class="text-muted mb-2">&lt;?= htmlspecialchars($user['email']) ?&gt;</p>
                    <div class="mb-3">
                        <span class="badge bg-primary">Level &lt;?= $user['level'] ?&gt;</span>
                        <span class="badge bg-success">&lt;?= $user['xp'] ?&gt; XP</span>
                    </div>
                    
                    <div class="progress mb-2" style="height: 10px;">
                        &lt;?php 
                        $nextLevelXP = pow($user['level'], 2) * 100;
                        $currentLevelXP = pow($user['level'] - 1, 2) * 100;
                        $progressXP = $user['xp'] - $currentLevelXP;
                        $requiredXP = $nextLevelXP - $currentLevelXP;
                        $percentage = ($progressXP / $requiredXP) * 100;
                        ?&gt;
                        <div class="progress-bar" style="width: &lt;?= $percentage ?&gt;%"></div>
                    </div>
                    <small class="text-muted">
                        &lt;?= $progressXP ?&gt; / &lt;?= $requiredXP ?&gt; XP ??n level ti?p theo
                    </small>
                </div>
            </div>
            
            <!-- Stats -->
            <div class="card border-0 shadow-sm" data-aos="fade-up">
                <div class="card-body">
                    <h6 class="mb-3"><i class="fas fa-chart-bar"></i> Th?ng k?</h6>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Kh?a h?c ?? ??ng k?</span>
                        <strong>&lt;?= $stats['enrolled_courses'] ?&gt;</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Kh?a h?c ho?n th?nh</span>
                        <strong>&lt;?= $stats['completed_courses'] ?&gt;</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Quiz ?? l?m</span>
                        <strong>&lt;?= $stats['total_quizzes'] ?&gt;</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Ch?ng ch?</span>
                        <strong>&lt;?= $stats['total_certificates'] ?&gt;</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Huy hi?u</span>
                        <strong>&lt;?= $stats['total_badges'] ?&gt;</strong>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-8">
            <!-- Update Profile -->
            <div class="card border-0 shadow-sm mb-4" data-aos="fade-up">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-user-edit"></i> C?p nh?t th?ng tin</h5>
                </div>
                <div class="card-body">
                    <form id="profile-form">
                        <div class="mb-3">
                            <label class="form-label">H? v? t?n</label>
                            <input type="text" class="form-control" name="name" value="&lt;?= htmlspecialchars($user['name']) ?&gt;" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" value="&lt;?= htmlspecialchars($user['email']) ?&gt;" disabled>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">S? ?i?n tho?i</label>
                            <input type="tel" class="form-control" name="phone" value="&lt;?= htmlspecialchars($user['phone'] ?? '') ?&gt;">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Gi?i thi?u</label>
                            <textarea class="form-control" name="bio" rows="3">&lt;?= htmlspecialchars($user['bio'] ?? '') ?&gt;</textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">?nh ??i di?n</label>
                            <input type="file" class="form-control" name="avatar" accept="image/*" 
                                   onchange="previewImage(this, 'avatar-preview')">
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> L?u thay ??i
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Badges -->
            <div class="card border-0 shadow-sm mb-4" data-aos="fade-up">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-medal"></i> Huy hi?u (&lt;?= count($badges) ?&gt;)</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        &lt;?php foreach ($badges as $badge): ?&gt;
                        <div class="col-md-4">
                            <div class="card border-warning">
                                <div class="card-body text-center">
                                    <i class="fas fa-trophy fa-3x text-warning mb-2"></i>
                                    <h6>&lt;?= htmlspecialchars($badge['name']) ?&gt;</h6>
                                    <small class="text-muted">&lt;?= htmlspecialchars($badge['description']) ?&gt;</small>
                                </div>
                            </div>
                        </div>
                        &lt;?php endforeach; ?&gt;
                    </div>
                </div>
            </div>
            
            <!-- Certificates -->
            <div class="card border-0 shadow-sm" data-aos="fade-up">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-certificate"></i> Ch?ng ch? (&lt;?= count($certificates) ?&gt;)</h5>
                </div>
                <div class="card-body">
                    &lt;?php if (empty($certificates)): ?&gt;
                        <p class="text-muted text-center">Ch?a c? ch?ng ch? n?o</p>
                    &lt;?php else: ?&gt;
                        &lt;?php foreach ($certificates as $cert): ?&gt;
                        <div class="d-flex align-items-center p-3 border rounded mb-2">
                            <i class="fas fa-certificate fa-3x text-primary me-3"></i>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">&lt;?= htmlspecialchars($cert['course_title']) ?&gt;</h6>
                                <small class="text-muted">C?p ng?y: &lt;?= date('d/m/Y', strtotime($cert['issued_at'])) ?&gt;</small><br>
                                <small class="text-muted">M?: &lt;?= $cert['certificate_code'] ?&gt;</small>
                            </div>
                            <a href="&lt;?= BASE_URL . 'public/uploads/certificates/' . $cert['file_path'] ?&gt;" 
                               class="btn btn-sm btn-outline-primary" target="_blank">
                                <i class="fas fa-download"></i> T?i xu?ng
                            </a>
                        </div>
                        &lt;?php endforeach; ?&gt;
                    &lt;?php endif; ?&gt;
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Update profile
document.getElementById('profile-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    try {
        const response = await fetch('&lt;?= BASE_URL ?&gt;auth/updateProfile', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            showNotification(result.message, 'success');
            setTimeout(() => location.reload(), 1500);
        } else {
            showNotification(result.message, 'error');
        }
    } catch (error) {
        showNotification('C? l?i x?y ra', 'error');
    }
});
</script>

&lt;?php require_once ROOT_PATH . 'views/layouts/footer.php'; ?&gt;
