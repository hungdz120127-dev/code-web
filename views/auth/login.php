&lt;?php require_once ROOT_PATH . 'views/layouts/header.php'; ?&gt;

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-lg border-0" data-aos="fade-up">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="fas fa-graduation-cap fa-3x text-primary"></i>
                        <h2 class="mt-3 fw-bold">??ng nh?p</h2>
                        <p class="text-muted">Ch?o m?ng b?n tr? l?i!</p>
                    </div>
                    
                    <form method="POST" action="&lt;?= BASE_URL ?&gt;auth/login">
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                <input type="email" class="form-control" name="email" required autofocus>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">M?t kh?u</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" class="form-control" name="password" required>
                            </div>
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember">
                            <label class="form-check-label" for="remember">Ghi nh? ??ng nh?p</label>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100 py-2">
                            <i class="fas fa-sign-in-alt"></i> ??ng nh?p
                        </button>
                    </form>
                    
                    <hr class="my-4">
                    
                    <div class="text-center">
                        <p class="mb-0">Ch?a c? t?i kho?n? <a href="&lt;?= BASE_URL ?&gt;auth/register" class="text-primary fw-bold">??ng k? ngay</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

&lt;?php require_once ROOT_PATH . 'views/layouts/footer.php'; ?&gt;
