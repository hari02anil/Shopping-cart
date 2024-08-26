<?php
use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';

// require 'path/to/PHPMailer/src/Exception.php';
// require 'path/to/PHPMailer/src/PHPMailer.php';
// require 'path/to/PHPMailer/src/SMTP.php';

$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp-relay.brevo.com'; // Mailtrap SMTP server
    $mail->SMTPAuth   = true;
    $mail->Username   = '7aea6a001@smtp-brevo.com'; // Replace with Mailtrap username
    $mail->Password   = 'v9VtEmaUFQRk5bYJ'; // Replace with Mailtrap password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption; or use `PHPMailer::ENCRYPTION_SMTPS` for SSL
    $mail->Port       = 2525; // Mailtrap's port (can be 2525, 587, or 465)

    // Recipients
    $mail->setFrom('teat22745@gmail.com', 'ShoppingCart');

    $mail->addAddress('vichuvishnu2023@gmail.com', ''); // Add a recipient

    // Content
    $mail->isHTML(true); // Set email format to HTML
    $mail->Subject = 'New products available';
    $mail->Body    = 'shop and enjoy <b>in bold!</b>';
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>