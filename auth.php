<?php
session_start();
require_once 'config.php';

// Regenerate session ID periodically to prevent fixation
if (!isset($_SESSION['CREATED'])) {
    $_SESSION['CREATED'] = time();
} else if (time() - $_SESSION['CREATED'] > 1800) {
    // Session started more than 30 minutes ago
    session_regenerate_id(true);    // change session ID for the current session and invalidate old session ID
    $_SESSION['CREATED'] = time();  // update creation time
}

function isAuthenticated()
{
    return isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true;
}

function requireLogin()
{
    if (!isAuthenticated()) {
        header("Location: index.php");
        exit;
    }
}
?>