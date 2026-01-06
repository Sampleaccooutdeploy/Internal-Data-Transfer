<?php
// This script helps you generate a valid hash for your server's PHP version.
$pass = 'Admin@123';
$hash = password_hash($pass, PASSWORD_DEFAULT);
?>
<div style="font-family: monospace; padding: 20px; background: #eee; border: 1px solid #ccc;">
    <h2>Password Setup</h2>
    <p>We generated a hash for the password: <strong>
            <?php echo $pass; ?>
        </strong> using YOUR server's PHP.</p>
    <p><strong>Step 1:</strong> Copy the text below:</p>
    <textarea style="width: 100%; height: 50px;"><?php echo $hash; ?></textarea>
    <p><strong>Step 2:</strong> Open <code>config.php</code> and replace the value of <code>ADMIN_PASSWORD_HASH</code>
        with this copied text.</p>
    <p><strong>Step 3:</strong> <a href="index.php">Go to Login</a></p>
</div>