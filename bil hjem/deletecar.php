<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bb_biler";

session_start(); // Start the session

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    $car_id = $_GET['car_id'] ?? null; // Get the car_id from the URL parameter

    // Perform deletion
    $stmt = $conn->prepare("DELETE FROM cars WHERE user_id = ? AND car_id = ?");
    
    if (!$stmt) {
        die("Error in SQL query: " . $conn->error);
    }

    $stmt->bind_param("si", $user_id, $car_id);
    $stmt->execute();

    $stmt->close();

    header("Location: editcars.php");
    exit();
}

$conn->close();
?>
