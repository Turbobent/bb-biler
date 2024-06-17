<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Menu Bar</title>
  <link rel="stylesheet" href="styles/style.css">
</head>

<body>
  <div class="topnav" id="header">
    <a class="active" href="index.php">Home</a>
    <a href="contact.php">Contact</a>
    <a href="about.html">About us</a>

    <?php
    session_start();

    if (isset($_SESSION['user_id'])) {
        // Connect to the database
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "bb_biler"; // Replace with your actual database name

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Fetch user funds
        $user_id = $_SESSION['user_id'];
        $query = "SELECT funds FROM users WHERE user_id = ?";
        $stmt = $conn->prepare($query);

        if (!$stmt) {
            die("Error in SQL query: " . $conn->error);
        }

        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($user_funds);
        $stmt->fetch();
        $stmt->close();

        echo '<span id="userFunds">Total Funds: $' . $user_funds . '</span>';

        // Close the database connection
        $conn->close();

        echo '<a href="logout.php" id="login">Log out</a>';
        echo '<a href="payment.php">Payments</a>';
        echo '<a href="create.php">Upload car</a>';
        echo '<a href="editcars.php">Edit car</a>';
        echo '<a href="profile.php" id="login">Profile</a>';
        echo '<a href="withdraw.php" id="withdraw">withdraw</a>';
    } else {
        echo '<a href="login.php" id="login">Login</a>';
    }
    ?>
  </div>
</body>

</html>
