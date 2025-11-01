    <!-- Footer -->
    <footer class="bg-dark text-white mt-5 py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <h5><i class="fas fa-graduation-cap"></i> &lt;?= APP_NAME ?&gt;</h5>
                    <p class="small">H? th?ng h?c t?p tr?c tuy?n th?ng minh cho m?i tr??ng h?c ???ng.</p>
                    <div class="social-links">
                        <a href="#" class="text-white me-3"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-youtube"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <h6>Li?n k?t</h6>
                    <ul class="list-unstyled small">
                        <li><a href="&lt;?= BASE_URL ?&gt;" class="text-white-50">Trang ch?</a></li>
                        <li><a href="&lt;?= BASE_URL ?&gt;course" class="text-white-50">Kh?a h?c</a></li>
                        <li><a href="&lt;?= BASE_URL ?&gt;forum" class="text-white-50">Di?n ??n</a></li>
                        <li><a href="&lt;?= BASE_URL ?&gt;home/about" class="text-white-50">Gi?i thi?u</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-3">
                    <h6>Li?n h?</h6>
                    <ul class="list-unstyled small text-white-50">
                        <li><i class="fas fa-envelope"></i> contact@elearning.com</li>
                        <li><i class="fas fa-phone"></i> +84 123 456 789</li>
                        <li><i class="fas fa-map-marker-alt"></i> H? N?i, Vi?t Nam</li>
                    </ul>
                </div>
            </div>
            <hr class="bg-secondary">
            <div class="text-center small">
                <p class="mb-0">&copy; 2024 &lt;?= APP_NAME ?&gt;. All rights reserved. Version &lt;?= APP_VERSION ?&gt;</p>
            </div>
        </div>
    </footer>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- AOS Animation -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <!-- Custom JS -->
    <script src="&lt;?= BASE_URL ?&gt;public/js/main.js"></script>
    
    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            once: true
        });
        
        // Check unread messages
        &lt;?php if (isset($user)): ?&gt;
        setInterval(function() {
            fetch('&lt;?= BASE_URL ?&gt;chat/unreadCount')
                .then(res => res.json())
                .then(data => {
                    if (data.count > 0) {
                        document.getElementById('unread-chat-badge').style.display = 'inline-block';
                        document.getElementById('unread-chat-badge').textContent = data.count;
                    } else {
                        document.getElementById('unread-chat-badge').style.display = 'none';
                    }
                });
        }, 5000);
        &lt;?php endif; ?&gt;
    </script>
</body>
</html>
