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

// Fetch total profit
$totalProfitQuery = "SELECT SUM(price) AS total_profit FROM cars WHERE sold = 1";
$totalProfitResult = $conn->query($totalProfitQuery);
$totalProfit = 0;

if ($totalProfitResult->num_rows > 0) {
    $totalProfitRow = $totalProfitResult->fetch_assoc();
    $totalProfit = $totalProfitRow['total_profit'];
}

// Fetch all sold cars
$soldCarsQuery = "SELECT car_id, model, manufacturer, year, price FROM cars WHERE sold = 1";
$soldCarsResult = $conn->query($soldCarsQuery);

// Fetch funds of user ID 7
$user7FundsQuery = "SELECT funds FROM users WHERE user_id = 7";
$user7FundsResult = $conn->query($user7FundsQuery);
$user7Funds = 0;

if ($user7FundsResult->num_rows > 0) {
    $user7FundsRow = $user7FundsResult->fetch_assoc();
    $user7Funds = $user7FundsRow['funds'];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profit Overview</title>
    <link rel="stylesheet" href="/styles/overview.css">
</head>
<body>
    <h2>Profit Overview</h2>
    <h3>profit: $<?php echo $user7Funds; ?></h3>
    <h3>Sold Cars:</h3>
    <table>
        <tr>
            <th>Car ID</th>
            <th>Model</th>
            <th>Manufacturer</th>
            <th>Year</th>
            <th>Price</th>
        </tr>
        <?php
        if ($soldCarsResult->num_rows > 0) {
            while ($row = $soldCarsResult->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['car_id'] . "</td>";
                echo "<td>" . $row['model'] . "</td>";
                echo "<td>" . $row['manufacturer'] . "</td>";
                echo "<td>" . $row['year'] . "</td>";
                echo "<td>$" . $row['price'] . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No sold cars found</td></tr>";
        }
        ?>
    </table>
    
    <form id="logout-form" action="/logout.php" method="post">
        <button id="logout-btn" type="submit">Logout</button>
    </form>
    <a href="allUsers.php"><button id="User-overview">User Overview</button></a>
    <a href="reportsOverview.php"><button id="reports-Overview">reports Overview</button></a>

</body>
</html>

<?php
$conn->close();
?>

