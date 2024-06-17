<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bb_biler";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if ticket ID is provided and valid
if(isset($_GET['id']) && is_numeric($_GET['id'])) {
    $ticket_id = $_GET['id'];
    
    // Prepare and execute query to fetch ticket details
    $stmt = $conn->prepare("SELECT ticket_id, problem, content ,status FROM tickets WHERE ticket_id = ?");
    $stmt->bind_param("i", $ticket_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if ticket exists
    if ($result->num_rows > 0) {
        $ticket = $result->fetch_assoc();
    } else {
        echo "Ticket not found.";
        exit;
    }

    // Close statement
    $stmt->close();
} else {
    echo "Invalid ticket ID.";
    exit;
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if status is provided and valid
    if(isset($_POST['status']) && in_array($_POST['status'], ['Open', 'Closed'])) {
        $new_status = $_POST['status'];

        // Prepare and execute query to update ticket status
        $update_stmt = $conn->prepare("UPDATE tickets SET status = ? WHERE ticket_id = ?");
        $update_stmt->bind_param("si", $new_status, $ticket_id);
        if ($update_stmt->execute()) {
            // Update successful
            $ticket['status'] = $new_status;
        } else {
            echo "Error updating ticket status: " . $conn->error;
        }
        // Close statement
        $update_stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket Details</title>
</head>
<body>
    <h1>Ticket Details</h1>
    <p><strong>Ticket ID:</strong> <?php echo $ticket['ticket_id']; ?></p>
    <p><strong>Status:</strong> <?php echo $ticket['status']; ?></p>
    <p><strong>Problem:</strong> <?php echo $ticket['problem']; ?></p>
    <p><strong>Content:</strong> <?php echo $ticket['content']; ?></p>
    
    <!-- Form to change ticket status -->
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $ticket_id; ?>" method="post">
        <label for="status">Change Status:</label>
        <select name="status" id="status">
            <option value="Open">Open</option>
            <option value="Closed">Closed</option>
        </select>
        <button type="submit">Update Status</button>
    </form>
    
    <a href="viewTickets.php">Go Back</a>
</body>
</html>
