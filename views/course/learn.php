<?php require_once ROOT_PATH . 'views/layouts/header.php'; ?>

<div class="container-fluid my-4">
    <div class="row">
        <!-- Sidebar - Lesson List -->
        <div class="col-lg-3 mb-4">
            <div class="card border-0 shadow-sm sticky-top" style="top: 100px;">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-list"></i> N?i dung kh?a h?c
                    </h6>
                </div>
                <div class="card-body p-0" style="max-height: 600px; overflow-y: auto;">
                    <div class="list-group list-group-flush">
                        <?php foreach ($lessons as $lesson): ?>
                        <a href="<?= BASE_URL ?>course/learn/<?= $course['id'] ?>/<?= $lesson['id'] ?>" 
                           class="list-group-item list-group-item-action <?= $currentLesson && $currentLesson['id'] == $lesson['id'] ? 'active' : '' ?>">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <?php if ($lesson['is_completed']): ?>
                                    <i class="fas fa-check-circle text-success"></i>
                                    <?php else: ?>
                                    <i class="fas fa-play-circle text-muted"></i>
                                    <?php endif; ?>
                                    <span class="ms-2"><?= htmlspecialchars($lesson['title']) ?></span>
                                </div>
                                <?php if ($lesson['duration'] > 0): ?>
                                <small class="text-muted"><?= $lesson['duration'] ?> ph?t</small>
                                <?php endif; ?>
                            </div>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="card-footer bg-light">
                    <div class="progress mb-2" style="height: 8px;">
                        <div class="progress-bar" style="width: <?= $enrollment['progress'] ?>%"></div>
                    </div>
                    <small class="text-muted">
                        Ho?n th?nh: <?= number_format($enrollment['progress'], 1) ?>%
                    </small>
                </div>
            </div>
        </div>

        <!-- Main Content - Video & Lesson -->
        <div class="col-lg-9">
            <?php if ($currentLesson): ?>
            
            <!-- Video Player -->
            <?php if ($currentLesson['video_url']): ?>
            <div class="card border-0 shadow-sm mb-4">
                <div class="ratio ratio-16x9 bg-dark">
                    <video id="lesson-video" class="w-100" controls controlsList="nodownload">
                        <source src="<?= $currentLesson['video_url'] ?>" type="video/mp4">
                        Tr?nh duy?t c?a b?n kh?ng h? tr? video.
                    </video>
                </div>
            </div>
            <?php endif; ?>

            <!-- Lesson Content -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h2 class="fw-bold mb-3"><?= htmlspecialchars($currentLesson['title']) ?></h2>
                    
                    <div class="d-flex gap-2 mb-4">
                        <?php if (!$currentLesson['is_completed']): ?>
                        <button class="btn btn-success" onclick="completeLesson(<?= $currentLesson['id'] ?>)">
                            <i class="fas fa-check"></i> ??nh d?u ho?n th?nh
                        </button>
                        <?php else: ?>
                        <span class="badge bg-success">
                            <i class="fas fa-check-circle"></i> ?? ho?n th?nh
                        </span>
                        <?php endif; ?>
                        
                        <button class="btn btn-outline-primary" onclick="showNoteModal()">
                            <i class="fas fa-sticky-note"></i> Ghi ch?
                        </button>
                        
                        <button class="btn btn-outline-secondary" onclick="bookmarkLesson()">
                            <i class="fas fa-bookmark"></i> ??nh d?u
                        </button>
                    </div>

                    <hr>

                    <!-- Lesson Content -->
                    <div class="lesson-content">
                        <?= $currentLesson['content'] ?>
                    </div>

                    <!-- Download Materials -->
                    <?php if ($currentLesson['document_path']): ?>
                    <div class="alert alert-info mt-4">
                        <i class="fas fa-file-download"></i>
                        <strong>T?i li?u:</strong>
                        <a href="<?= BASE_URL . 'public/uploads/documents/' . $currentLesson['document_path'] ?>" 
                           class="alert-link" download>
                            T?i xu?ng t?i li?u
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Navigation -->
            <div class="d-flex justify-content-between mb-4">
                <?php
                $prevLesson = null;
                $nextLesson = null;
                foreach ($lessons as $index => $lesson) {
                    if ($lesson['id'] == $currentLesson['id']) {
                        if ($index > 0) $prevLesson = $lessons[$index - 1];
                        if ($index < count($lessons) - 1) $nextLesson = $lessons[$index + 1];
                        break;
                    }
                }
                ?>
                
                <?php if ($prevLesson): ?>
                <a href="<?= BASE_URL ?>course/learn/<?= $course['id'] ?>/<?= $prevLesson['id'] ?>" 
                   class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left"></i> B?i tr??c
                </a>
                <?php else: ?>
                <div></div>
                <?php endif; ?>

                <?php if ($nextLesson): ?>
                <a href="<?= BASE_URL ?>course/learn/<?= $course['id'] ?>/<?= $nextLesson['id'] ?>" 
                   class="btn btn-primary">
                    B?i ti?p theo <i class="fas fa-arrow-right"></i>
                </a>
                <?php endif; ?>
            </div>

            <!-- Lesson Discussion -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-comments"></i> Th?o lu?n</h5>
                </div>
                <div class="card-body">
                    <form id="discussion-form" class="mb-4">
                        <textarea class="form-control" name="content" rows="3" 
                                  placeholder="??t c?u h?i ho?c chia s? ? ki?n..." required></textarea>
                        <button type="submit" class="btn btn-primary mt-2">
                            <i class="fas fa-paper-plane"></i> G?i
                        </button>
                    </form>

                    <div id="discussions-list">
                        <!-- Discussions will be loaded here -->
                        <div class="text-center text-muted py-3">
                            <i class="fas fa-comments fa-2x mb-2"></i>
                            <p>Ch?a c? th?o lu?n n?o</p>
                        </div>
                    </div>
                </div>
            </div>

            <?php else: ?>
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i>
                Vui l?ng ch?n b?i h?c t? danh s?ch b?n tr?i ?? b?t ??u h?c.
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Note Modal -->
<div class="modal fade" id="noteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-sticky-note"></i> Ghi ch?
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="note-form">
                <div class="modal-body">
                    <input type="hidden" name="lesson_id" value="<?= $currentLesson['id'] ?? '' ?>">
                    <input type="hidden" name="timestamp" id="video-timestamp">
                    <textarea class="form-control" name="content" rows="4" 
                              placeholder="Nh?p ghi ch? c?a b?n..." required></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">??ng</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> L?u ghi ch?
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const BASE_URL = '<?= BASE_URL ?>';
const noteModal = new bootstrap.Modal(document.getElementById('noteModal'));

// Video player
const video = document.getElementById('lesson-video');
if (video) {
    // Save watch progress
    video.addEventListener('timeupdate', () => {
        const progress = (video.currentTime / video.duration) * 100;
        localStorage.setItem('lesson_<?= $currentLesson['id'] ?? 0 ?>_progress', video.currentTime);
    });

    // Resume from last position
    const savedProgress = localStorage.getItem('lesson_<?= $currentLesson['id'] ?? 0 ?>_progress');
    if (savedProgress) {
        video.currentTime = parseFloat(savedProgress);
    }
}

// Show note modal
function showNoteModal() {
    if (video) {
        document.getElementById('video-timestamp').value = Math.floor(video.currentTime);
    }
    noteModal.show();
}

// Save note
document.getElementById('note-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = Object.fromEntries(formData);
    
    try {
        const response = await fetch(BASE_URL + 'note/create', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            showNotification('L?u ghi ch? th?nh c?ng', 'success');
            noteModal.hide();
            this.reset();
        } else {
            showNotification(result.message, 'error');
        }
    } catch (error) {
        showNotification('C? l?i x?y ra', 'error');
    }
});

// Complete lesson
async function completeLesson(lessonId) {
    try {
        const response = await fetch(BASE_URL + 'course/completeLesson/' + lessonId, {
            method: 'POST'
        });
        
        const result = await response.json();
        
        if (result.success) {
            showNotification(result.message + ' (+' + result.xp_earned + ' XP)', 'success');
            
            if (result.course_completed) {
                Swal.fire({
                    icon: 'success',
                    title: 'Ch?c m?ng!',
                    text: 'B?n ?? ho?n th?nh kh?a h?c!',
                    confirmButtonText: 'Xem ch?ng ch?'
                }).then(() => {
                    window.location.href = BASE_URL + 'auth/profile';
                });
            } else {
                setTimeout(() => location.reload(), 1500);
            }
        }
    } catch (error) {
        showNotification('C? l?i x?y ra', 'error');
    }
}

// Bookmark lesson
async function bookmarkLesson() {
    showNotification('T?nh n?ng ?ang ph?t tri?n', 'info');
}
</script>

<?php require_once ROOT_PATH . 'views/layouts/footer.php'; ?>
