<?php
session_start();
require_once 'config.php';

// Redirect if already logged in
if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
    header("Location: dashboard.php");
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Verify credentials
    if ($username === ADMIN_USERNAME && password_verify($password, ADMIN_PASSWORD_HASH)) {
        $_SESSION['user_logged_in'] = true;
        session_regenerate_id(true);
        $_SESSION['CREATED'] = time();
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Invalid credentials. Please contact administration.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Internal Login | Data Transfer System</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

    <div class="login-wrapper">
        <div class="login-card">
            <div class="login-header">
                <div class="mb-3 text-primary d-inline-block p-2 rounded-circle bg-light border">
                    <i class="bi bi-shield-lock-fill fs-3 d-flex"></i>
                </div>
                <h4 class="fw-bold mb-1">Internal Login</h4>
                <p class="text-muted small mb-0">Internal Data Transfer System</p>
            </div>

            <div class="login-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger py-2 px-3 small border-0 bg-danger-subtle text-danger rounded-2 mb-4 d-flex align-items-center">
                        <i class="bi bi-exclamation-circle-fill me-2"></i> 
                        <span><?php echo htmlspecialchars($error); ?></span>
                    </div>
                <?php endif; ?>

                <form method="POST" action="index.php">
                    <!-- Username -->
                    <div class="mb-4">
                        <label for="username" class="form-label">Username</label>
                        <div class="custom-input-group">
                            <span class="input-icon">
                                <i class="bi bi-person"></i>
                            </span>
                            <input type="text" class="form-control" id="username" name="username" 
                                   required autofocus placeholder="Enter your username" autocomplete="username">
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="mb-4">
                        <label for="password" class="form-label">Password</label>
                        <div class="custom-input-group">
                            <span class="input-icon">
                                <i class="bi bi-lock"></i>
                            </span>
                            <input type="password" class="form-control" id="password" name="password" 
                                   required placeholder="Enter your password" autocomplete="current-password">
                            <button class="btn-toggle" type="button" id="togglePassword" aria-label="Show password">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn-security">
                        <i class="bi bi-shield-check"></i> Secure Access
                    </button>
                </form>
            </div>

            <div class="login-footer">
                <i class="bi bi-lock-fill me-1"></i> Authorized Personnel Only
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');
        const icon = togglePassword.querySelector('i');

        togglePassword.addEventListener('click', function () {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            
            if (type === 'password') {
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            } else {
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            }
        });
    </script>
</body>
</html>