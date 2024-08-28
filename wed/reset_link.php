<?php
include 'db.php'; 
include 'user_log.php';
session_start();
use PHPMailer\PHPMailer\PHPMailer;
require 'vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    // Check if the email exists in the users table
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND role = 'user'");

    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        // Generate a unique token
        $token = bin2hex(random_bytes(32));
        date_default_timezone_set('Asia/Kolkata');

        // Set expiration time
        $expires_at = date('Y-m-d H:i:s', strtotime('+5 minutes'));

        // Insert the token into the database
        $stmt = $pdo->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)");
        $stmt->execute([$email, $token, $expires_at]);

        // Create the reset link
        $reset_link = "localhost/shopping/reset_pass.php?token=" . $token;
        //log
        log_user($pdo, $user['id'], 'Password reset link generated');

        $mail = new PHPMailer(true);

        
            // Server settings
            $mail->isSMTP();
            $mail->Host       = 'smtp-relay.brevo.com'; // Mailtrap SMTP server
            $mail->SMTPAuth   = true;
            $mail->Username   = '7aea6a001@smtp-brevo.com'; // Replace with Mailtrap username
            $mail->Password   = 'v9VtEmaUFQRk5bYJ'; // Replace with Mailtrap password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption; or use `PHPMailer::ENCRYPTION_SMTPS` for SSL
            $mail->Port       = 2525; // Mailtrap's port (can be 2525, 587, or 465)
        
            
        
            $mail->setFrom('teat22745@gmail.com', 'ShoppingCart');
        
            $mail->addAddress($email, ''); // Add  recipient
        
            $mail->Subject = 'Password Reset';
            $mail->Body    ="Click on the link to reset your password   <a href =".  $reset_link .">Reset link</a>";
            $mail->send();
            echo json_encode("true");
    } else {
        echo json_encode("false");
    }
}
?>
