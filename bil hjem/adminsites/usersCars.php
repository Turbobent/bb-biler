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

// Fetch user ID from the query string
if(isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];
} else {
    echo "User ID not provided.";
    exit();
}

// Fetch cars associated with the user
$carsQuery = "SELECT * FROM cars WHERE user_id = $user_id";
$carsResult = $conn->query($carsQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Cars</title>
    <link rel="stylesheet" href="/styles/usersCars.css">

</head>
<body>
    <div class="container">
        <h2>Cars for Sale by User</h2>
        <table>
            <tr>
                <th>Car ID</th>
                <th>Model</th>
                <th>Manufacturer</th>
                <th>Year</th>
                <th>Price</th>
                <th>Image</th>
                <th>KM</th>
                <th>Sold</th>
                <th>Seller</th>
                <th>Buyer</th>
            </tr>
            <?php
            if ($carsResult->num_rows > 0) {
                while ($row = $carsResult->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['car_id'] . "</td>";
                    echo "<td>" . $row['model'] . "</td>";
                    echo "<td>" . $row['manufacturer'] . "</td>";
                    echo "<td>" . $row['year'] . "</td>";
                    echo "<td>" . $row['price'] . "</td>";
                    echo "<td>" . $row['image'] . "</td>";
                    echo "<td>" . $row['km'] . "</td>";
                    echo "<td>" . $row['sold'] . "</td>";
                    echo "<td>" . $row['seller'] . "</td>";
                    echo "<td>" . $row['buyer'] . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='10'>No cars found for this user</td></tr>";
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
