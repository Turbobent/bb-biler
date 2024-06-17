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

// Fetch reported user information
$reported_user_id = isset($_GET['reported_user_id']) ? $_GET['reported_user_id'] : null;
if ($reported_user_id) {
    $stmt_user = $conn->prepare("SELECT username FROM users WHERE user_id = ?");
    $stmt_user->bind_param("i", $reported_user_id);
    $stmt_user->execute();
    $result_user = $stmt_user->get_result();
    if ($result_user->num_rows > 0) {
        $reported_user = $result_user->fetch_assoc();
    } else {
        $reported_user = null;
    }
    $stmt_user->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $car_id = $_POST['car_id'];
    $user_id = $_SESSION['user_id'];
    $report_content = $_POST['report_content'];
    $report_reason = $_POST['report_reason']; // Add report reason
    $reported_user_id = $_POST['reported_user_id'];

    $stmt = $conn->prepare("INSERT INTO reports (user_id, reported_user_id, car_id, content, reason) VALUES (?, ?, ?, ?, ?)"); // Update query
    $stmt->bind_param("iiiss", $user_id, $reported_user_id, $car_id, $report_content, $report_reason); // Update binding

    if ($stmt->execute()) {
        echo "Report submitted successfully.";
    } else {
        echo "Error submitting report: " . $conn->error;
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
    <title>Report Car</title>
    <link rel="stylesheet" href="styles/report.css">
    <script>
        $(function () {
            $("#header").load("navbar.php");
        });
</script>
</head>

<body>
<div id="header"></div>

    <div class="container">
        <h2>Report Car</h2>
        <form action="report.php" method="post">
            <input type="hidden" name="report_id" value="">
            <input type="hidden" name="car_id" value="<?php echo isset($_GET['car_id']) ? $_GET['car_id'] : ''; ?>">
            <input type="hidden" name="reported_user_id" value="<?php echo isset($_GET['reported_user_id']) ? $_GET['reported_user_id'] : ''; ?>">
            <label for="reported_user">Reported User:</label><br>
            <input type="text" id="reported_user" name="reported_user" value="<?php echo isset($reported_user['username']) ? $reported_user['username'] : ''; ?>" readonly><br>
            <label for="report_reason">Report Reason:</label><br> <!-- Add label for report reason -->
            <textarea id="report_reason" name="report_reason" rows="1" cols="50" required></textarea><br> <!-- Add textarea for report reason -->
            <label for="report_content">Report Content:</label><br>
            <textarea id="report_content" name="report_content" rows="4" cols="50" required></textarea><br>
            <button type="submit">Submit Report</button>
        </form>
        <a href="index.php"><button>Home</button></a>

    </div>
</body>

</html>
