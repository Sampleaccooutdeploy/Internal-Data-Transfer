<?php
// Utility script to generate password hashes
// Run this file in your browser or command line to generate a hash for a new password.
// e.g. php generate_password.php?password=MyNewPassword

$password = $_GET['password'] ?? 'Admin@123'; // Default to Admin@123 if not provided
$hash = password_hash($password, PASSWORD_DEFAULT);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Generate Password Hash</title>
    <style>
        body {
            font-family: monospace;
            padding: 20px;
            background: #f4f4f4;
        }

        .box {
            background: #fff;
            padding: 20px;
            border: 1px solid #ccc;
            max-width: 600px;
            word-break: break-all;
        }
    </style>
</head>

<body>
    <div class="box">
        <h3>Password Hash Generator</h3>
        <p><strong>Password:</strong>
            <?php echo htmlspecialchars($password); ?>
        </p>
        <p><strong>Hash:</strong>
            <?php echo htmlspecialchars($hash); ?>
        </p>
        <hr>
        <p>Copy the hash above and paste it into <code>config.php</code> as the value for
            <code>ADMIN_PASSWORD_HASH</code>.</p>
        <p><em>To generate a different hash, add <code>?password=YOUR_PASSWORD</code> to the URL.</em></p>
    </div>
</body>

</html>