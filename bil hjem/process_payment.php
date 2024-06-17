<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "bb_biler";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $user_id = $_SESSION['user_id'];
    if (empty($user_id)) {
        die("User ID is not set in the session.");
    }
    // Assuming you have a form field named "funds" for the amount to deduct
    $amountToDeduct = $_POST['funds'];

    // Validate if $amountToDeduct is a positive integer
    if (!ctype_digit($amountToDeduct) || $amountToDeduct <= 0) {
        die("Invalid amount to deduct");
    }

    // Update user funds in the database
    $updateFunds = $conn->prepare("UPDATE users SET funds = COALESCE(funds, 0) + ? WHERE user_id = ?");
    $updateFunds->bind_param("ii", $amountToDeduct, $user_id);

    if (!$updateFunds) {
        die("Error in SQL query: " . $conn->error);
    }

    $updateFunds->bind_param("ii", $amountToDeduct, $user_id);

    if (!$updateFunds->execute()) {
        die("Error updating funds: " . $updateFunds->error);
    }

    echo '<script>window.location.href = "index.php";</script>'; 
    $updateFunds->close();
    $conn->close();
}
?>