<?php
require_once 'auth.php';
requireLogin();

if (isset($_GET['id'])) {
    $fileId = $_GET['id'];

    // 1. Read Metadata
    $metadata = [];
    if (file_exists(METADATA_FILE)) {
        $jsonContent = file_get_contents(METADATA_FILE);
        $metadata = json_decode($jsonContent, true) ?? [];
    }

    // 2. Find File
    $fileData = null;
    foreach ($metadata as $file) {
        if ($file['id'] === $fileId) {
            $fileData = $file;
            break;
        }
    }

    if ($fileData) {
        $filePath = UPLOAD_DIR . $fileData['stored_name'];

        if (file_exists($filePath)) {
            // 3. Serve File
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            // Force download with ORIGINAL name
            header('Content-Disposition: attachment; filename="' . $fileData['original_name'] . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filePath));
            readfile($filePath);
            exit;
        } else {
            die("Error: File not found on server disk.");
        }
    } else {
        die("Error: File ID not found.");
    }
} else {
    header("Location: dashboard.php");
    exit;
}
?>