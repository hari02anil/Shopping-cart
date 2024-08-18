




<?php
session_start();
include 'db.php'; // Adjust path if necessary



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Extract form data
    $name = $_POST['name'] ?? '';
    $dob = $_POST['dob'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $agree_policy = $_POST['agree_policy'] ?? '';

    // Validate input
    if (empty($name) || empty($dob) || empty($gender) || empty($email) || empty($phone) || empty($username) || empty($password) || empty($agree_policy)) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
        exit();
    }

    // Check if the email or username already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email OR username = :username");
    $stmt->execute(['email' => $email, 'username' => $username]);
    if ($stmt->rowCount() > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Email or username already exists.']);
        exit();
    }

    // Handle file upload
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['profile_picture']['tmp_name'];
        $fileName = $_FILES['profile_picture']['name'];
        $fileSize = $_FILES['profile_picture']['size'];
        $fileType = $_FILES['profile_picture']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));
        $allowedExts = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($fileExtension, $allowedExts)) {
            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
            $uploadFileDir = 'uploads';
            $destPath = $uploadFileDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $destPath)) {
                $profile_picture_path = $destPath;
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to move uploaded file.']);
                exit();
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid file type.']);
            exit();
        }
    } else {
        $profile_picture_path = null; // No file uploaded
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    try {
        // Insert user data into the database
        $stmt = $pdo->prepare("
            INSERT INTO users (name, dob, gender, email, phone, username, password, profile_photo)
            VALUES (:name, :dob, :gender, :email, :phone, :username, :password, :profile_picture)
        ");
        $stmt->execute([
            'name' => $name,
            'dob' => $dob,
            'gender' => $gender,
            'email' => $email,
            'phone' => $phone,
            'username' => $username,
            'password' => $hashed_password,
            'profile_picture' => $profile_picture_path
        ]);

        echo json_encode(['status' => 'success', 'message' => 'Registration successful!']);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'An error occurred: ' . $e->getMessage()]);
    }
} else {
 
    echo json_encode(['status' => 'error', 'message' => 'Request method: ' . $_SERVER['REQUEST_METHOD']]);
    
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>