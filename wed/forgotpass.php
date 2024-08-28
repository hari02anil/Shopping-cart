<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


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
            overflow: hidden; /* Hide any overflow to avoid scrollbars */
        }

        /* Form Container Styling */
        .form-container {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 100%;
            max-width: 400px; /* Adjust to fit within viewport */
            box-sizing: border-box; /* Include padding in width calculation */
        }

        /* Form Element Styling */
        form {
            display: flex;
            flex-direction: column;
            gap: 15px; /* Space between elements */
        }

        input[type="text"],
        input[type="email"] {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 10px;
            font-size: 1em;
            box-sizing: border-box; /* Include padding in width calculation */
        }

        input[type="submit"] {
            background-color: #007bff;
            border: none;
            color: #fff;
            padding: 10px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1em;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        input[type="reset"] {
            background-color: #007bff;
            border: none;
            color: #fff;
            padding: 10px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1em;
        }

        input[type="reset"]:hover {
            background-color: #0056b3;
        }

        /* Error Message Styling */
        .error-message {
            color: #d9534f; /* Bootstrap danger color */
            font-size: 0.9em;
            margin-top: 10px;
        }
    </style>
    
</head>
<body>
<div class="form-container">
    <h1><center>Forgot Password</h1>
    <form action="reset_link.php" method="POST">
        
        <input type="email" id="email" name="email" placeholder ="Enter your email" >
        <input type="submit" class ="resetMail" value="Send Reset Link">
        <input type="reset" onclick="window.location.href='login.php';" value ="Cancel">
    </form>
    </div>
</body>
<script>
    $(document).ready(function() {

        // Handle "mail" button click
        $(".resetMail").click(function() {
            var email = $("#email").val(); 
            
            $.ajax({
                url: 'reset_link.php',
                method: 'POST',
                data: {
                    email: email,
                    
                },
                success: function(response) {
                    var result = JSON.parse(response);
                    if(result ==="true"){
                        alert("Reset link sent to registered email");
                        window.location.href = 'home.php';
                    }else if(result==="false"){
                        alert("Email not found");
                        location.reload();
                    }else{
                    
                    }
                    
                    
                },
                error: function(response) {
                    alert(response.message);
                    location.reload();
                }
            });
        });

    });
    </script>
</html>
