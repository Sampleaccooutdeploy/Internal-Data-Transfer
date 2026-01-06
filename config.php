<?php
// Configuration File

// Prevent direct access
if (count(get_included_files()) == 1) {
    header('HTTP/1.0 403 Forbidden');
    exit;
}

// Global Settings
define('TIMEZONE', 'Asia/Kolkata');
date_default_timezone_set(TIMEZONE);

// Directories
define('UPLOAD_DIR', __DIR__ . '/uploads/');
define('DATA_DIR', __DIR__ . '/data/');
define('METADATA_FILE', DATA_DIR . 'files.json');

// File Constraints
define('MAX_FILE_SIZE', 10 * 1024 * 1024 * 1024); // 10GB
define('ALLOWED_EXTENSIONS', ['zip', 'pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png']);

// Admin Credentials
define('ADMIN_USERNAME', 'admin');

// PERMANENT HASH FOR 'Admin@123' (Generated on User System)
// DO NOT CHANGE THIS LINE
define('ADMIN_PASSWORD_HASH', '$2y$10$wRC60epd0Q8DVlM5Gk4l2OmfoDdlWeCQXrK/WrRgi3vTMCc18mubq');

// Security Headers
header("X-XSS-Protection: 1; mode=block");
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: SAMEORIGIN");
?>