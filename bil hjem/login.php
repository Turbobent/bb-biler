<?php
session_start(); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "bb_biler";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT user_id, username, password_hash FROM users WHERE username = ?");

    if (!$stmt) {
        die("Error in SQL query: " . $conn->error);
    }

    $stmt->bind_param("s", $username);

    $username = $_POST['user_username'];
    $plain_password = $_POST['user_password_hash']; 

    $stmt->execute();
    $stmt->bind_result($userid, $db_username, $db_password_hash);

    if ($stmt->fetch() && password_verify($plain_password, $db_password_hash)) {
        // Check if the user is user with ID 7
        if ($userid == 7) {
            header("Location: adminsites/overview.php");
            exit();
        } 
        // Check if the user is user with ID 8
        elseif ($userid == 8) {
            $_SESSION['user_id'] = $userid;
            $_SESSION['username'] = $db_username;
            header("Location: adminsites/viewTickets.php");
            exit();
        } else {
            $_SESSION['user_id'] = $userid;
            $_SESSION['username'] = $db_username;
            header("Location: index.php");
            exit();
        }
    } 

    $stmt->close();
    $conn->close();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/login style.css">
    <title>Login</title>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        $(function () {
            $("#header").load("navbar.php");
            $("#footer").load("footer.html");
        });
    </script>
</head>
<body>
    <div id="header"></div>

    <h1>Login</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
    <!-- Headings for the form -->
    <div class="headingsContainer">
        <h3>Sign in</h3>
        <p>Sign in with your username and password</p>
    </div>

    <!-- Main container for all inputs -->
    <div class="mainContainer">
        <!-- Username -->
        <label for="username">Your username</label>
        <input type="text" placeholder="Enter Username" name="user_username" required>

        <br><br>

        <!-- Password -->
        <label for="pswrd">Your password</label>
        <input type="password" placeholder="Enter Password" name="user_password_hash" required>

        <!-- sub container for the checkbox and forgot password link -->
        <div class="subcontainer">
            <label>
                <input type="checkbox" checked="checked" name="remember"> Remember me
            </label>
            <p class="forgotpsd"> <a href="forgot password.html">Forgot Password?</a></p>
        </div>

        <!-- Submit button -->
        <button type="submit">Login</button>

        <!-- Sign up link -->
        <p class="register">Not a member?  <a href="register.php">Register here!</a></p>
    </div>
</form>

    <div id="footer"></div>
</body>
<script src="js/Login.js"></script>
</html>
