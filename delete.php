<?php
require_once 'auth.php';
requireLogin();

if (isset($_GET['id'])) {
    $fileId = $_GET['id'];

    // 1. Load Metadata
    $metadata = [];
    if (file_exists(METADATA_FILE)) {
        $jsonContent = file_get_contents(METADATA_FILE);
        $metadata = json_decode($jsonContent, true) ?? [];
    }

    // 2. Find and Remove File
    $foundIndex = -1;
    $fileToDelete = null;

    foreach ($metadata as $index => $file) {
        if ($file['id'] === $fileId) {
            $foundIndex = $index;
            $fileToDelete = $file;
            break;
        }
    }

    if ($fileToDelete) {
        // Remove physical file
        $filePath = UPLOAD_DIR . $fileToDelete['stored_name'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // Remove from metadata array
        array_splice($metadata, $foundIndex, 1);

        // Save back to JSON
        if (file_put_contents(METADATA_FILE, json_encode($metadata, JSON_PRETTY_PRINT))) {
            $_SESSION['upload_message'] = "File '{$fileToDelete['original_name']}' deleted successfully.";
            $_SESSION['upload_message_type'] = "success";
        } else {
            $_SESSION['upload_message'] = "Error updating metadata file.";
            $_SESSION['upload_message_type'] = "error";
        }
    } else {
        $_SESSION['upload_message'] = "File not found.";
        $_SESSION['upload_message_type'] = "error";
    }
}

header("Location: dashboard.php");
exit;
?>