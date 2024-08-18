<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    
    // Handle file upload
    $profilePhoto = $_FILES['profile_photo'];
    $photoPath = null;
    if ($profilePhoto['error'] == UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        $photoPath = $uploadDir . basename($profilePhoto['name']);
        move_uploaded_file($profilePhoto['tmp_name'], $photoPath);
    }

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format");
    }

    //validate phone
    function validatePhoneNumber($phone) {
        // Define a regex pattern for phone numbers
        $pattern = '/^(?:\+91|91)?(?:\s|\-)?(?:\(?\d{3}\)?|\d{3})?\s?\d{10}$/';
    
        return preg_match($pattern, $phone);
    }
    if (!validatePhoneNumber($phone)) {
        die("Invalid phone number format");
    }




    
 // Hash the password
 $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Insert user into database
  $sql = "INSERT INTO users (name, dob, gender, profile_photo, email, phone, username, password) 
  VALUES (:name, :dob, :gender, :profile_photo, :email, :phone, :username, :password)";
$stmt = $pdo->prepare($sql);
$stmt->execute([
'name' => $name,
'dob' => $dob,
'gender' => $gender,
'profile_photo' => $photoPath,
'email' => $email,
'phone' => $phone,
'username' => $username,
'password' => $hashed_password
]);

    // Redirect to the home page with a success message
    header('Location: home.php?message=' . urlencode('Registration successful!  Now you can login with credentials'));
    exit();
}
?>

<html>
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
  <style>

    
        /* Password Criteria Styling */
        #passwordCriteria {
            display: none; /* Ensure the message is hidden initially */
            font-size: 0.9em;
            color: #d9534f; /* Bootstrap danger color */
            border: 1px solid #d9534f;
            padding: 10px;
            margin-top: 5px;
            border-radius: 4px;
            background-color: #f9f4f4; /* Light red background */
        }
    </style>
</head>
    <body>

<form method="POST" enctype="multipart/form-data">
    <input type="text" name="name" placeholder="Name" required><br>
    <input type="date" name="dob" placeholder="Date of Birth" required><br>
    <select name="gender" required>
        <option value="male">Male</option>
        <option value="female">Female</option>
        <option value="other">Other</option>
    </select><br>
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="text" name="username" placeholder="Username" required><br>
    <input type="tel" name="phone" pattern="(?:\+?\d{0,2})?\s?\d{10}"placeholder="Phone number" required><br>
    <input type="password" id="password" name="password"
           minlength="8" 
           maxlength="20" 
           pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,20}"
           placeholder="Enter your password"
           required
           onfocus="showPasswordCriteria()"
           onblur="hidePasswordCriteria()"><br>
    <div id="passwordCriteria">
        Password must be between 8 and 20 characters long, and include:<br>
        - At least one uppercase letter<br>
        - At least one lowercase letter<br>
        - At least one number<br>
        - At least one special character (e.g., @, $, !, %, *, ?, &)
    </div>
    <input type="file" name="profile_photo" accept="image/*"><br>
    <input type="checkbox" name="agree" required> I agree to the policy<br>
    <input type="submit" value="Register">
    <input type="reset" value="Cancel">
</form>

<script>
    function showPasswordCriteria() {
        document.getElementById('passwordCriteria').style.display = 'block';
    }

    function hidePasswordCriteria() {
        document.getElementById('passwordCriteria').style.display = 'none';
    }
</script>
