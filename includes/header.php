<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Internal Data Transfer System</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/style.css">
</head>

<body class="bg-light">

    <?php if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true): ?>
        <!-- Navbar for Logged In Users -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm mb-4">
            <div class="container">
                <a class="navbar-brand fw-bold" href="dashboard.php">
                    <i class="bi bi-shield-lock-fill me-2"></i>Internal Data Transfer System
                </a>
                <div class="d-flex align-items-center">
                    <span class="text-white-50 me-3 d-none d-md-block small text-uppercase fw-bold">Admin Access</span>
                    <a href="logout.php" class="btn btn-sm btn-light text-primary fw-bold">
                        <i class="bi bi-box-arrow-right me-1"></i>Sign Out
                    </a>
                </div>
            </div>
        </nav>
    <?php endif; ?>

    <main class="container">