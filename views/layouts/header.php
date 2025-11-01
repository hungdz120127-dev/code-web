&lt;!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>&lt;?= $title ?? APP_NAME ?&gt;</title>
    
    <!-- Preconnect for Performance -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- Google Fonts - Poppins, Inter, Nunito Sans -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&family=Nunito+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="&lt;?= BASE_URL ?&gt;public/css/style.css">
    <link rel="stylesheet" href="&lt;?= BASE_URL ?&gt;public/css/custom.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Meta Tags -->
    <meta name="description" content="&lt;?= APP_NAME ?&gt; - H? th?ng h?c t?p tr?c tuy?n th?ng minh">
    <meta name="keywords" content="e-learning, h?c tr?c tuy?n, kh?a h?c online">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="&lt;?= BASE_URL ?&gt;">
                <i class="fas fa-graduation-cap"></i> &lt;?= APP_NAME ?&gt;
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    <li class="nav-item">
                        <a class="nav-link" href="&lt;?= BASE_URL ?&gt;">
                            <i class="fas fa-home"></i> Trang ch?
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="&lt;?= BASE_URL ?&gt;course">
                            <i class="fas fa-book"></i> Kh?a h?c
                        </a>
                    </li>
                    
                    &lt;?php if (isset($user)): ?&gt;
                        <li class="nav-item">
                            <a class="nav-link" href="&lt;?= BASE_URL ?&gt;forum">
                                <i class="fas fa-comments"></i> Di?n ??n
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link position-relative" href="&lt;?= BASE_URL ?&gt;chat">
                                <i class="fas fa-envelope"></i> Tin nh?n
                                <span class="badge bg-danger position-absolute top-0 start-100 translate-middle" id="unread-chat-badge" style="display:none;">0</span>
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <img src="&lt;?= BASE_URL . 'public/uploads/avatars/' . $user['avatar'] ?&gt;" 
                                     class="rounded-circle" width="32" height="32" alt="Avatar"
                                     onerror="this.src='&lt;?= BASE_URL ?&gt;public/images/default-avatar.png'">
                                &lt;?= htmlspecialchars($user['name']) ?&gt;
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="&lt;?= BASE_URL ?&gt;dashboard"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                                <li><a class="dropdown-item" href="&lt;?= BASE_URL ?&gt;auth/profile"><i class="fas fa-user"></i> H? s?</a></li>
                                &lt;?php if ($user['role'] === 'admin'): ?&gt;
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="&lt;?= BASE_URL ?&gt;admin"><i class="fas fa-cog"></i> Qu?n tr?</a></li>
                                &lt;?php endif; ?&gt;
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="&lt;?= BASE_URL ?&gt;auth/logout"><i class="fas fa-sign-out-alt"></i> ??ng xu?t</a></li>
                            </ul>
                        </li>
                        <li class="nav-item ms-lg-2">
                            <button class="btn btn-sm" id="theme-toggle">
                                <i class="fas fa-moon"></i>
                            </button>
                        </li>
                    &lt;?php else: ?&gt;
                        <li class="nav-item">
                            <a class="nav-link" href="&lt;?= BASE_URL ?&gt;auth/login">
                                <i class="fas fa-sign-in-alt"></i> ??ng nh?p
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-light btn-sm" href="&lt;?= BASE_URL ?&gt;auth/register">
                                ??ng k?
                            </a>
                        </li>
                    &lt;?php endif; ?&gt;
                </ul>
            </div>
        </div>
    </nav>
    
    <!-- Flash Messages -->
    &lt;?php
    $flashTypes = ['success', 'error', 'info', 'warning'];
    foreach ($flashTypes as $type) {
        if (isset($_SESSION['flash_' . $type])) {
            $message = $_SESSION['flash_' . $type];
            unset($_SESSION['flash_' . $type]);
            $alertType = $type === 'error' ? 'danger' : $type;
            echo "&lt;div class='alert alert-{$alertType} alert-dismissible fade show m-3' role='alert'&gt;";
            echo htmlspecialchars($message);
            echo "&lt;button type='button' class='btn-close' data-bs-dismiss='alert'&gt;&lt;/button&gt;";
            echo "&lt;/div&gt;";
        }
    }
    ?&gt;
