<?php
require_once 'auth.php';
requireLogin();

// Ensure upload directory exists
if (!is_dir(UPLOAD_DIR)) {
    mkdir(UPLOAD_DIR, 0755, true);
}

// Ensure data directory exists
if (!is_dir(DATA_DIR)) {
    mkdir(DATA_DIR, 0755, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uploadedFiles = [];
    $errors = [];
    $totalUploaded = 0;

    // Load existing metadata
    $currentFiles = [];
    if (file_exists(METADATA_FILE)) {
        $json = file_get_contents(METADATA_FILE);
        $currentFiles = json_decode($json, true) ?? [];
    }

    // Check if any files were sent
    if (isset($_FILES['files']) && !empty($_FILES['files']['name'][0])) {

        $count = count($_FILES['files']['name']);

        // Loop through each file
        for ($i = 0; $i < $count; $i++) {
            $fileName = $_FILES['files']['name'][$i];
            $fileTmpPath = $_FILES['files']['tmp_name'][$i];
            $fileSize = $_FILES['files']['size'][$i];
            $fileError = $_FILES['files']['error'][$i];
            $fileType = $_FILES['files']['type'][$i];

            if ($fileError === UPLOAD_ERR_OK) {

                // Get extension
                $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                // No file size limit (software level check is redundant if config allows it, but kept for sanity)
                // MAX_FILE_SIZE is set to 10GB in config.php

                if ($fileSize > MAX_FILE_SIZE) {
                    $errors[] = "$fileName is too large (limit: 10GB).";
                    continue;
                }

                // Security: Basic extension filtering can be done here if needed. 
                // Currently ALLOWED_EXTENSIONS is in config.php. 
                // If you want to strictly enforce it, uncomment below:
                /*
                if (!in_array($fileExt, ALLOWED_EXTENSIONS)) {
                    $errors[] = "$fileName has an invalid extension.";
                    continue;
                }
                */

                // Create Unique ID and Path
                $uniqueId = uniqid() . '-' . bin2hex(random_bytes(4));
                $newFileName = $uniqueId . '.' . $fileExt;
                $destPath = UPLOAD_DIR . $newFileName;

                if (move_uploaded_file($fileTmpPath, $destPath)) {
                    // Add to metadata
                    $newFile = [
                        'id' => $uniqueId,
                        'original_name' => $fileName,
                        'stored_name' => $newFileName,
                        'type' => $fileExt,
                        'size' => $fileSize,
                        'uploaded_on' => date('Y-m-d H:i:s')
                    ];

                    // Prepend to list (newest first)
                    array_unshift($currentFiles, $newFile);
                    $totalUploaded++;
                } else {
                    $errors[] = "Failed to move $fileName to upload directory.";
                }

            } else {
                $errors[] = "Error uploading $fileName (Code: $fileError).";
            }
        }

        // Save metadata
        file_put_contents(METADATA_FILE, json_encode($currentFiles, JSON_PRETTY_PRINT));

        // Set session message
        if ($totalUploaded > 0) {
            $_SESSION['upload_message'] = "Successfully uploaded $totalUploaded file(s).";
            $_SESSION['upload_message_type'] = "success";
        } else {
            $_SESSION['upload_message'] = "Upload failed. " . implode(" ", $errors);
            $_SESSION['upload_message_type'] = "error";
        }

    } else {
        $_SESSION['upload_message'] = "No files selected or file upload size exceeded server limit.";
        $_SESSION['upload_message_type'] = "error";
    }

    header("Location: dashboard.php");
    exit;
}
?>