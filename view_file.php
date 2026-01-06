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
            // Determine MIME Type based on extension
            $ext = strtolower($fileData['type']);
            $mime_types = [
                'pdf' => 'application/pdf',
                'jpg' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'png' => 'image/png',
                'gif' => 'image/gif',
                'txt' => 'text/plain',
                'doc' => 'application/msword',
                'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'zip' => 'application/zip'
            ];

            $contentType = $mime_types[$ext] ?? 'application/octet-stream';

            // Serve the file inline
            header('Content-Type: ' . $contentType);
            header('Content-Disposition: inline; filename="' . $fileData['original_name'] . '"');
            header('Content-Length: ' . filesize($filePath));
            header('Cache-Control: private, max-age=0, must-revalidate');
            header('Pragma: public');

            readfile($filePath);
            exit;
        } else {
            die("Error: File not found on server disk.");
        }
    } else {
        die("Error: File ID not found.");
    }
}
?>