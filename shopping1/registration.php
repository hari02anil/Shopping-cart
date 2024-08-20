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
    
    // file uploading
    $profilePhoto = $_FILES['profile_photo'];
    $photoPath = null;
    if ($profilePhoto['error'] == UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        $photoPath = $uploadDir . basename($profilePhoto['name']);
        move_uploaded_file($profilePhoto['tmp_name'], $photoPath);
    }

   




    
 // Hash the password
 $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Insert user into database
  $stmt = $pdo->prepare("INSERT INTO users (name, dob, gender, profile_photo, email, phone, username, password) VALUES (:name, :dob, :gender, :profile_photo, :email, :phone, :username, :password)");

$stmt->bindParam(':name', $name);
$stmt->bindParam(':dob', $dob);
$stmt->bindParam(':gender', $gender);
$stmt->bindParam(':profile_photo', $photoPath);
$stmt->bindParam(':email', $email);
$stmt->bindParam(':phone', $phone);
$stmt->bindParam(':username', $username);
$stmt->bindParam(':password', $hashed_password);

$stmt->execute();

    // Redirect to the home page with a success message
    header('Location: home.php?message=' . urlencode('Registration successful!  Now you can login with credentials'));
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="script1.js"></script> 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <style>
        /* General Body Styling */
        body {
            background-image: url('shop img.jpg');
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        /* Form Container Styling */
        .form-container {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 100%;
            max-width: 500px;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        /* Form Element Styling */
        form {
            display: flex;
            flex-direction: column;
        }
        select:invalid { color: gray; }

        input[type="text"],
        input[type="date"],
        input[type="email"],
        input[type="tel"],
        input[type="password"],
        select,
        input[type="file"] {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 10px;
            margin-bottom: 15px;
            font-size: 1em;
        }

        input[type="submit"],
        input[type="reset"] {
            background-color: #007bff;
            border: none;
            color: #fff;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1em;
            margin-right: 10px;
        }

        input[type="submit"]:hover,
        input[type="reset"]:hover {
            background-color: #0056b3;
        }

        /* Checkbox Styling */
        input[type="checkbox"] {
            margin-right: 10px;
        }

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

<div class="form-container"><br><br><br><br><br><br><br><br>
    <h1><center>User Registration</h1>
    <form method="POST" enctype="multipart/form-data">
    <input type="text" id="name" name="name" required placeholder="name">
    <span id="nameError" style="color: red; display: none;">Invalid name. Must start with a letter, contain no consecutive three digits, and no special characters allowed.</span>
    <div id="nameCriteria" style="color: grey;">
        <p id="startsWithLetter" class="invalid"></p>
        <p id="noConsecutiveNumbers" class="invalid"></p>
        <p id="noSpecialCharacters" class="invalid"></p>
    </div>
    <br>
        <input type="date" name="dob" placeholder="Date of Birth" required><br>
        <select name="gender" required >
        <option value="" disabled selected hidden>Gender</option>
            <option value="male">Male</option>
            <option value="female">Female</option>
            <option value="other">Other</option>
        </select><br>
        <input type="email" name="email" id ="email" placeholder="Email" required>
        <span id="emailError" style="color: red; display: none;">Please enter a valid email address.</span>
        <span id="email-status"></span><br>


        <input type="text" name="username" id="username" placeholder="Username" required><br>
        <span id="username-status"></span><br>


        <input type="tel" name="phone" pattern="(?:\+?\d{0,2})?\s?\d{10}" placeholder="Phone number" required  onkeypress="return isNumberKey(event)"><br>
        
        <input type="password" id="password" name="password" required 
           minlength="8" maxlength="20" placeholder ="password" 
           pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,20}">
    <span id="passwordError" style="color: red; display: none;">Your password must be 8-20 characters long, contain at least one uppercase letter, one lowercase letter, one number, and one special character.</span>
    <div id="passwordCriteria" style="color: grey;">
        <p id="length" class="invalid">At least 8 characters long</p>
        <p id="uppercase" class="invalid">At least one uppercase letter</p>
        <p id="lowercase" class="invalid">At least one lowercase letter</p>
        <p id="number" class="invalid">At least one number</p>
        <p id="special" class="invalid">At least one special character (@$!%*?&)</p>
    </div>
    <br>

        <input type="file" name="profile_photo" accept="image/*"><br>
        <input type="checkbox" name="agree" required><center><a href ="policy.html"> I agree to the policy</a><br><br>
        <input type="submit" value="Register">
        <input type="reset" onclick="window.location.href='home.php';" value ="cancel">
    </form>
</div>

<script>
   

    function isNumberKey(evt) {
  var charCode = (evt.which) ? evt.which : evt.keyCode
  if (charCode > 31 && (charCode < 48 || charCode > 57))
    return false;
  return true;
}

document.getElementById('email').addEventListener('blur', function() {
    var emailField = document.getElementById('email');
    var emailError = document.getElementById('emailError');
    
    if (!emailField.validity.valid) {
        emailError.style.display = 'inline';
    } else {
        emailError.style.display = 'none';
    }
});


document.getElementById('name').addEventListener('input', function() {
    var name = document.getElementById('name').value;
    
    var startsWithLetter = document.getElementById('startsWithLetter');
    var noConsecutiveNumbers = document.getElementById('noConsecutiveNumbers');
    var noSpecialCharacters = document.getElementById('noSpecialCharacters');
    var nameError = document.getElementById('nameError');
    
    // Check if name starts with a letter
    var startsWithLetterRegex = /^[a-zA-Z]/;
    if (startsWithLetterRegex.test(name)) {
        startsWithLetter.classList.remove('invalid');
        startsWithLetter.classList.add('valid');
    } else {
        startsWithLetter.classList.remove('valid');
        startsWithLetter.classList.add('invalid');
    }
    
    // Check for consecutive three digits
    var noConsecutiveNumbersRegex = /(\d{3})/;
    if (noConsecutiveNumbersRegex.test(name)) {
        noConsecutiveNumbers.classList.remove('valid');
        noConsecutiveNumbers.classList.add('invalid');
    } else {
        noConsecutiveNumbers.classList.remove('invalid');
        noConsecutiveNumbers.classList.add('valid');
    }
    
    // Check for special characters
    var noSpecialCharactersRegex = /^[a-zA-Z0-9]*$/;
    if (noSpecialCharactersRegex.test(name)) {
        noSpecialCharacters.classList.remove('invalid');
        noSpecialCharacters.classList.add('valid');
    } else {
        noSpecialCharacters.classList.remove('valid');
        noSpecialCharacters.classList.add('invalid');
    }
    
    // Show or hide error message based on all criteria
    if (startsWithLetter.classList.contains('valid') &&
        noConsecutiveNumbers.classList.contains('valid') &&
        noSpecialCharacters.classList.contains('valid')) {
        nameError.style.display = 'none';
    } else {
        nameError.style.display = 'inline';
    }
});




document.getElementById('password').addEventListener('input', function() {
    var password = document.getElementById('password').value;
    
    var lengthCriteria = document.getElementById('length');
    var uppercaseCriteria = document.getElementById('uppercase');
    var lowercaseCriteria = document.getElementById('lowercase');
    var numberCriteria = document.getElementById('number');
    var specialCriteria = document.getElementById('special');
    var passwordError = document.getElementById('passwordError');
    
    // Check length
    if (password.length >= 8) {
        lengthCriteria.classList.remove('invalid');
        lengthCriteria.classList.add('valid');
    } else {
        lengthCriteria.classList.remove('valid');
        lengthCriteria.classList.add('invalid');
    }
    
    // Check uppercase letter
    if (/[A-Z]/.test(password)) {
        uppercaseCriteria.classList.remove('invalid');
        uppercaseCriteria.classList.add('valid');
    } else {
        uppercaseCriteria.classList.remove('valid');
        uppercaseCriteria.classList.add('invalid');
    }
    
    // Check lowercase letter
    if (/[a-z]/.test(password)) {
        lowercaseCriteria.classList.remove('invalid');
        lowercaseCriteria.classList.add('valid');
    } else {
        lowercaseCriteria.classList.remove('valid');
        lowercaseCriteria.classList.add('invalid');
    }
    
    // Check number
    if (/\d/.test(password)) {
        numberCriteria.classList.remove('invalid');
        numberCriteria.classList.add('valid');
    } else {
        numberCriteria.classList.remove('valid');
        numberCriteria.classList.add('invalid');
    }
    
    // Check special character
    if (/[@$!%*?&]/.test(password)) {
        specialCriteria.classList.remove('invalid');
        specialCriteria.classList.add('valid');
    } else {
        specialCriteria.classList.remove('valid');
        specialCriteria.classList.add('invalid');
    }
    
    // If all criteria are met
    if (password.length >= 8 && /[A-Z]/.test(password) && /[a-z]/.test(password) && /\d/.test(password) && /[@$!%*?&]/.test(password)) {
        passwordError.style.display = 'none';
    } else {
        passwordError.style.display = 'inline';
    }
});








</script>

</body>
</html>



