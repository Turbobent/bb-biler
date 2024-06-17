<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "bb_biler";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO users (username, password_hash, email, first_name, last_name, contact, gender, profile_picture) VALUES (?, ?, ?, ?, ?, ?,?,?)");

    if (!$stmt) {
        die("Error in SQL query: " . $conn->error);
    }

    // Assign values before binding
    $username = $_POST['reg_username'];
    $password_hash = password_hash($_POST['reg_password_hash'], PASSWORD_DEFAULT);
    $email = $_POST['reg_email'];
    $first_name = $_POST['reg_first_name'];
    $last_name = $_POST['reg_last_name'];
    $contact = $_POST['reg_contact'];
    $gender = $_POST['reg_gender'];
    $profile_picture = 'stdpfp.jpg'; // Default profile picture path

    // Check for duplicate email
    $email_check_stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $email_check_stmt->bind_param("s", $email);
    $email_check_stmt->execute();
    $email_result = $email_check_stmt->get_result();

    if ($email_result->num_rows > 0) {
        echo "Email already exists";
    } else {
        // Check for duplicate username
        $username_check_stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $username_check_stmt->bind_param("s", $username);
        $username_check_stmt->execute();
        $username_result = $username_check_stmt->get_result();

        if ($username_result->num_rows > 0) {
            echo "Username already exists";
        } else {
            // Check for duplicate phone number
            $contact_check_stmt = $conn->prepare("SELECT * FROM users WHERE contact = ?");
            $contact_check_stmt->bind_param("s", $contact);
            $contact_check_stmt->execute();
            $contact_result = $contact_check_stmt->get_result();

            if ($contact_result->num_rows > 0) {
                echo "Phone number already exists";
            } else {
                // Insert user data
                $stmt->bind_param("ssssssss", $username, $password_hash, $email, $first_name, $last_name, $contact, $gender, $profile_picture);
                $stmt->execute();

                echo "New user created successfully";
            }
        }
    }

    $stmt->close();
    $conn->close();
}
?>


<!DOCTYPE html> 
<html> 
  
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <link rel="stylesheet" href="/styles/register.css">
    
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        $(function () {
            $("#header").load("/navbar.php");
            $("#footer").load("/footer.html");
        });
    </script>
</head>
  
<body> 
   
    <div id="header"></div>
    <div class="container">
    <div class="main"> 
        
        <h1>Register</h1> 
        <form action="register.php" method="POST">
            <label for="first">First Name:</label> 
            <input type="text" id="first" 
                   name="reg_first_name" 
                   placeholder="Enter your first name" required> 
  
            <label for="last">Last Name:</label> 
            <input type="text" id="last" 
                   name="reg_last_name" 
                   placeholder="Enter your last name" required> 

                   <label for="username">Username:</label> 
            <input type="text" id="last" 
                   name="reg_username" 
                   placeholder="Enter your username" required> 

            <label for="email">Email:</label> 
            <input type="email" id="email" 
                   name="reg_email" 
                   placeholder="Enter your email" required> 
  
  
            <label for="password">Password:</label> 
            <input type="password" id="password" 
                   name="reg_password_hash"
                   placeholder="Enter your password"
                   pattern= 
                   "^(?=.*\d)(?=.*[a-zA-Z])(?=.*[^a-zA-Z0-9])\S{8,}$" required                    
                   title="Password must contain at least one number,  
                       one alphabet, one symbol, and be at  
                       least 8 characters long"> 
   
  
            <label for="mobile">Contact:</label> 
            <input type="text" id="mobile" 
                   name="reg_contact" 
                   placeholder="Enter your Mobile Number" required 
                   maxlength="10"> 
  
            <label for="gender">Gender:</label> 
            <select id="gender" name="reg_gender" required> 
                <option value="male">Male</option> 
                <option value="female">Female</option> 
                <option value="other">Other</option> 
            </select> 
  
            <div class="wrap"> 
                <button type="submit" onclick="solve()"> 
                  Submit 
                  </button> 
            </div> 
        </form> 
    </div> 
    </div>
    <script src="script.js"></script> 
  
    <div id="footer"></div>
</body> 
  
</html>