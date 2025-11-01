&lt;?php require_once ROOT_PATH . 'views/layouts/header.php'; ?&gt;

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-lg border-0" data-aos="fade-up">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="fas fa-user-plus fa-3x text-primary"></i>
                        <h2 class="mt-3 fw-bold">??ng k? t?i kho?n</h2>
                        <p class="text-muted">T?o t?i kho?n mi?n ph? ngay h?m nay</p>
                    </div>
                    
                    <form method="POST" action="&lt;?= BASE_URL ?&gt;auth/register">
                        <div class="mb-3">
                            <label class="form-label">H? v? t?n</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Vai tr?</label>
                            <select class="form-select" name="role" required>
                                <option value="student">H?c vi?n</option>
                                <option value="teacher">Gi?o vi?n</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">M?t kh?u</label>
                            <input type="password" class="form-control" name="password" required minlength="6">
                            <small class="text-muted">T?i thi?u 6 k? t?</small>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">X?c nh?n m?t kh?u</label>
                            <input type="password" class="form-control" name="confirm_password" required>
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="terms" required>
                            <label class="form-check-label" for="terms">
                                T?i ??ng ? v?i <a href="#" class="text-primary">?i?u kho?n s? d?ng</a>
                            </label>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100 py-2">
                            <i class="fas fa-user-plus"></i> ??ng k?
                        </button>
                    </form>
                    
                    <hr class="my-4">
                    
                    <div class="text-center">
                        <p class="mb-0">?? c? t?i kho?n? <a href="&lt;?= BASE_URL ?&gt;auth/login" class="text-primary fw-bold">??ng nh?p</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

&lt;?php require_once ROOT_PATH . 'views/layouts/footer.php'; ?&gt;
