<?php
session_start();

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

// Retrieve the user_id from the URL parameter
if (isset($_GET['User_id'])) {
    $user_id = $_GET['User_id'];
} else {
    die("User ID not specified.");
}

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ban_reason = $_POST['ban_reason'];
    $ban_time = $_POST['ban_time'];
    $banned = 1;

    // Prepare an update statement
    $updateQuery = "UPDATE users SET banned = ?, ban_reason = ?, ban_time = ? WHERE user_id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("issi", $banned, $ban_reason, $ban_time, $user_id);

    if ($stmt->execute()) {
        $message = "User banned successfully.";
    } else {
        $message = "Error banning user: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ban User</title>
    <link rel="stylesheet" href="/styles/banUser.css">
</head>
<body>
    <div class="container">
        <h1>Ban User</h1>
        <?php if (isset($message)): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="ban_time">Ban until</label>
                <input type="date" id="ban_time" name="ban_time" required>
            </div>
            <div class="form-group">
                <label for="ban_reason">Ban Reason</label>
                <textarea id="ban_reason" name="ban_reason" rows="4" required></textarea>
            </div>
            <div class="form-group">
                <button type="submit">Ban User</button>
            </div>
        </form>
    </div>
</body>
</html>
