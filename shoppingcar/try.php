<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
</head>
<body>
    <h2>Register</h2>
    <form method = "POST" id="registration-form" >
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required><br>

        <label for="dob">Date of Birth:</label>
        <input type="date" id="dob" name="dob" required><br>

        <label for="gender">Gender:</label>
        <select id="gender" name="gender" required>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
        </select><br>

        <label for="profile_picture">Profile Picture:</label>
        <input type="file" id="profile_picture" name="profile_picture" accept="image/*"><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br>

        <label for="phone">Phone:</label>
        <input type="tel" id="phone" name="phone" required><br>

        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br>

        <label>
            <input type="checkbox" id="agree_policy" name="agree_policy" required> Agree to Policy
        </label><br>

        <button type="submit">Register</button>
        <button type="button" onclick="window.location.href='login.php'">Cancel</button>
    </form>

    <script > $(document).ready(function() {
    $('#registration-form').submit(function(event) {
        event.preventDefault(); // Prevent the default form submission

        var formData = new FormData(this); // Create a FormData object from the form

        $.ajax({
            url: 'register.php', // PHP script to handle registration
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                var result = JSON.parse(response); // Parse the JSON response
                if (result.status === 'success') {
                    alert('Registration successful!');
                    window.location.href = 'login.php'; // Redirect to login page
                } else {
                    alert('Error: ' + result.message); // Show error message
                }
            },
            error: function() {
                alert('An error occurred. Please try again.');
            }
        });
    });
});

</script>
</body>
</html>