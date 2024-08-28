<?php
include 'db.php'; 
include 'user_log.php';

// Get the token from the URL
$token = $_GET['token'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Fetch the new password from the form
    $new_password = password_hash($_POST['new_password'], PASSWORD_BCRYPT);

    // Look up the token in the database
    $stmt = $pdo->prepare("SELECT email FROM password_resets WHERE token = ? AND expires_at > NOW()");
    $stmt->execute([$token]);
    $result = $stmt->fetch();


    

    if ($result) {
        // Token is valid, update the user's password
        $email = $result['email'];
        $update_stmt = $pdo->prepare("UPDATE users SET password = ? WHERE email = ?");
        $update_stmt->execute([$new_password, $email]);

        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND role = 'user'");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        log_user($pdo, $user['id'], 'Password Updated');

        // Delete the token so it can't be reused
        $delete_stmt = $pdo->prepare("DELETE FROM password_resets WHERE token = ?");
        $delete_stmt->execute([$token]);

        echo "<script>
    alert('Password has been reset');
    window.location.href = 'login.php'; 
    </script>";
    } else {
        
        echo "<script>
    alert('This password reset link is invalid or has expired');
    window.location.href = 'home.php'; 
    </script>";
    }
} else {
    // Display the reset form
    echo '
    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
        body {
        background-image: url("shop img.jpg");
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .reset-container {
            background-color: #fff;
            padding: 20px 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }

        .reset-container h2 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
            text-align: center;
        }

        .reset-container label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }

        .reset-container input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            color: #333;
        }

        .reset-container button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .reset-container button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="reset-container">
    <h2>Reset Password</h2>
    <form method="POST">
        <label for="new_password">New Password:</label>
        <input type="password" name="new_password" required>
        <button type="submit">Reset Password</button>
    </form>
</div>

</body>
</html>';

}
?>
