<?php
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

// Fetch all users
$usersQuery = "SELECT * FROM reports";
$usersResult = $conn->query($usersQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Overview</title>
    <link rel="stylesheet" href="/styles/allUsers.css">

</head>
<body>
    <div class="container">
        <h2>Report Overview</h2>
        <table>
            <tr>
                <th>Report ID</th>
                <th>User id</th>
                <th>Reported for</th>
                <th>Content</th>
                <th>Reported user id</th>
                <th>Reported car id</th>
            </tr>
            <?php
            if ($usersResult->num_rows > 0) {
                while ($row = $usersResult->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['report_id'] . "</td>";
                    echo "<td>" . $row['user_id'] . "</td>";
                    echo "<td>" . $row['Reason'] . "</td>";
                    echo "<td>" . $row['content'] . "</td>";
                    echo "<td>" . $row['reported_user_id'] . "</td>";
                    echo "<td>" . $row['car_id'] . "</td>";

                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='8'>No reports found</td></tr>";
            }
            ?>
        </table>
        <a href="overview.php"><button>Go Back</button></a>
    </div>
</body>
</html>

<?php
$conn->close();
?>
