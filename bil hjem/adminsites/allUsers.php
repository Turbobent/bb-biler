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
$usersQuery = "SELECT * FROM users";
$usersResult = $conn->query($usersQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Overview</title>
    <link rel="stylesheet" href="/styles/allUsers.css">

</head>
<body>
    <div class="container">
        <h2>User Overview</h2>
        <table>
            <tr>
                <th>User ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Contact</th>
                <th>Gender</th>
                <th>Funds</th>
                <th>Banned</th>
                <th>Ban time</th>
                <th>Ban reason</th>
                <th>Ban</th>

            </tr>
            <?php
            if ($usersResult->num_rows > 0) {
                while ($row = $usersResult->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['user_id'] . "</td>";
                    echo "<td><a href='usersCars.php?user_id=" . $row['user_id'] . "'>" . $row['username'] . "</a></td>";
                    echo "<td>" . $row['email'] . "</td>";
                    echo "<td>" . $row['first_name'] . "</td>";
                    echo "<td>" . $row['last_name'] . "</td>";
                    echo "<td>" . $row['contact'] . "</td>";
                    echo "<td>" . $row['gender'] . "</td>";
                    echo "<td>$" . $row['funds'] . "</td>";
                    echo "<td>" . $row['banned'] . "</td>";
                    echo "<td>" . $row['ban_time'] . "</td>";
                    echo "<td>" . $row['ban_reason'] . "</td>";
                    echo '<td><a href="banUsers.php?User_id='. $row["user_id"] . '">Ban</a></td>'; 
                    
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='8'>No users found</td></tr>";
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
