<?php require_once ROOT_PATH . 'views/layouts/header.php'; ?>

<div class="container my-4">
    <div class="row mb-4">
        <div class="col-lg-8">
            <h2 class="fw-bold"><i class="fas fa-tasks"></i> B?i t?p</h2>
            <p class="text-muted">Qu?n l? v? theo d?i b?i t?p c?a b?n</p>
        </div>
        <div class="col-lg-4 text-end">
            <?php if ($user['role'] === 'teacher'): ?>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createAssignmentModal">
                <i class="fas fa-plus"></i> T?o b?i t?p m?i
            </button>
            <?php endif; ?>
        </div>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <select class="form-select" id="filter-status">
                        <option value="">T?t c? tr?ng th?i</option>
                        <option value="pending">Ch?a n?p</option>
                        <option value="submitted">?? n?p</option>
                        <option value="graded">?? ch?m</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <select class="form-select" id="filter-course">
                        <option value="">T?t c? kh?a h?c</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control" placeholder="T?m ki?m..." id="search-input">
                </div>
            </div>
        </div>
    </div>

    <!-- Assignments List -->
    <div class="row g-4">
        <?php if (empty($assignments)): ?>
        <div class="col-12">
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i>
                Ch?a c? b?i t?p n?o
            </div>
        </div>
        <?php else: ?>
            <?php foreach ($assignments as $assignment): ?>
            <div class="col-md-6 col-lg-4" data-aos="fade-up">
                <div class="card h-100 border-0 shadow-sm assignment-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h5 class="card-title mb-0"><?= htmlspecialchars($assignment['title']) ?></h5>
                            <?php if ($user['role'] === 'student'): ?>
                                <?php if ($assignment['student_status'] === 'pending'): ?>
                                    <span class="badge bg-warning">Ch?a n?p</span>
                                <?php elseif ($assignment['student_status'] === 'submitted'): ?>
                                    <span class="badge bg-info">?? n?p</span>
                                <?php else: ?>
                                    <span class="badge bg-success">?? ch?m</span>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="badge bg-primary"><?= $assignment['submission_count'] ?? 0 ?> b?i n?p</span>
                            <?php endif; ?>
                        </div>

                        <p class="card-text text-muted small">
                            <?= substr(htmlspecialchars($assignment['description']), 0, 100) ?>...
                        </p>

                        <div class="mb-3">
                            <small class="text-muted">
                                <i class="fas fa-calendar"></i>
                                H?n: <?= date('d/m/Y H:i', strtotime($assignment['due_date'])) ?>
                            </small>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge bg-light text-dark">
                                <i class="fas fa-star"></i> <?= $assignment['max_points'] ?> ?i?m
                            </span>
                            
                            <?php if ($user['role'] === 'student' && isset($assignment['grade'])): ?>
                                <span class="badge bg-success">
                                    ?i?m: <?= $assignment['grade'] ?>/<?= $assignment['max_points'] ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-0">
                        <a href="<?= BASE_URL ?>assignment/view/<?= $assignment['id'] ?>" 
                           class="btn btn-primary w-100">
                            <?php if ($user['role'] === 'student'): ?>
                                <?php if ($assignment['student_status'] === 'pending'): ?>
                                    <i class="fas fa-upload"></i> N?p b?i
                                <?php else: ?>
                                    <i class="fas fa-eye"></i> Xem chi ti?t
                                <?php endif; ?>
                            <?php else: ?>
                                <i class="fas fa-check-circle"></i> Ch?m b?i
                            <?php endif; ?>
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<style>
.assignment-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.assignment-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15) !important;
}
</style>

<?php require_once ROOT_PATH . 'views/layouts/footer.php'; ?>
