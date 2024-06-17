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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Fetch data from the form
    $car_id = $_POST['car_id'];
    $model = $_POST['car_model'];
    $manufacturer = $_POST['car_manufacturer'];
    $year = $_POST['car_year'];
    $price = $_POST['car_price'];
    $km = $_POST['car_km'];

    // Update the record in the database
    $stmt = $conn->prepare("UPDATE cars SET model=?, manufacturer=?, year=?, price=?, km=? WHERE user_id=? AND car_id=?");

    if (!$stmt) {
        die("Error in SQL query: " . $conn->error);
    }

    $stmt->bind_param("ssssssi", $model, $manufacturer, $year, $price, $km, $user_id, $car_id);
    $stmt->execute();

    $stmt->close();
}

// Fetch car details to pre-fill the form
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $car_id = $_GET['car_id'];

    $stmt = $conn->prepare("SELECT model, manufacturer, year, price, km FROM cars WHERE user_id=? AND car_id=?");

    if (!$stmt) {
        die("Error in SQL query: " . $conn->error);
    }

    $stmt->bind_param("si", $user_id, $car_id);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $model = $row["model"];
        $manufacturer = $row["manufacturer"];
        $year = $row["year"];
        $price = $row["price"];
        $km = $row["km"];
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
    <title>Edit Car</title>
    <link rel="stylesheet" href="styles/editCar.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        $(function () {
            $("#header").load("navbar.php");
            $("#footer").load("footer.html");
        });
    </script>
</head>

<body>
    <div id="header"></div>

    <h2>Edit Car</h2>
    <form id="editCarForm" action="editcarinfo.php" method="post">
        <!-- Hidden field to store car_id -->
        <input type="hidden" name="car_id" value="<?php echo $car_id; ?>">

        <!-- Car Information -->
        <label for="carModel">Car Model:</label>
        <input type="text" id="carModel" name="car_model" value="<?php echo $model; ?>" required>

        <label for="manufacturer">Manufacturer:</label>
        <input type="text" id="manufacturer" name="car_manufacturer" value="<?php echo $manufacturer; ?>" required>

        <label for="year">Year:</label>
        <input type="number" id="year" name="car_year" value="<?php echo $year; ?>" required>

        <label for="price">Price:</label>
        <input type="text" id="price" name="car_price" value="<?php echo $price; ?>" required>

        <label for="km">Km:</label>
        <input type="number" id="km" name="car_km" value="<?php echo $km; ?>" required>

        <input type="submit" value="Update">
    </form>
    <div id="footer"></div>
</body>

</html>

