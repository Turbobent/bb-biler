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


$open_query = "SELECT ticket_id, problem FROM tickets WHERE status = 'Open'";
$open_result = $conn->query($open_query);

if (!$open_result) {
    die("Open query failed: " . $conn->error);
}

$num_open_tickets = $open_result->num_rows;

$closed_query = "SELECT ticket_id, problem FROM tickets WHERE  status = 'Closed'";
$closed_result = $conn->query($closed_query);

if (!$closed_result) {
    die("Closed query failed: " . $conn->error);
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket View</title>
    <link rel="stylesheet" href="/styles/viewTickets.css">
</head>
<body>
    <!-- Logout button -->
    <form action="/logout.php" method="post">
        <button type="submit">Logout</button>
    </form>

    <div>
        <h2>Open Tickets (<?php echo $num_open_tickets; ?>)</h2>
        <?php
        if ($open_result->num_rows > 0) {
            while ($row = $open_result->fetch_assoc()) {
                echo '<div class="ticket">';
                echo '<p>Ticket ID: <a href="ticket_details.php?id=' . $row["ticket_id"] . '">' . $row["ticket_id"] . '</a></p>';
                echo '<p>Status: Open</p>';
                echo '<p>Problem: ' . $row["problem"] . '</p>';
                echo '</div>'; 
            }
        } else {
            echo "<p>No open tickets.</p>";
        }
        ?>
    </div>

    <div>
        <h2>Closed Tickets</h2>
        <?php
        if ($closed_result->num_rows > 0) {
            while ($row = $closed_result->fetch_assoc()) {
                echo '<div class="ticket">';
                echo '<p>Ticket ID: <a href="ticket_details.php?id=' . $row["ticket_id"] . '">' . $row["ticket_id"] . '</a></p>';
                echo '<p>Status: Closed</p>';
                echo '<p>Problem: ' . $row["problem"] . '</p>';
                echo '</div>';
            }
        } else {
            echo "<p>No closed tickets.</p>";
        }
        ?>
    </div>
</body>
</html>
