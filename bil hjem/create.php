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

    $stmt = $conn->prepare("INSERT INTO cars (user_id, model, manufacturer, year, price, image, km) VALUES (?, ?, ?, ?, ?, ?, ?)");

    if (!$stmt) {
        die("Error in SQL query: " . $conn->error);
    }

   
    $model = $_POST['car_model'];
    $manufacturer = $_POST['car_manufacturer'];
    $year = $_POST['car_year'];
    $price = $_POST['car_price'];
    $image = ''; 
    $km = $_POST['car_km'];

    
    $uploadDir = 'uploads/';
    $uploadFile = $uploadDir . basename($_FILES['car_image']['name']);

    if (move_uploaded_file($_FILES['car_image']['tmp_name'], $uploadFile)) {
        $image = $_FILES['car_image']['name'];
        echo "File is valid, and was successfully uploaded.";
    } else {
        die("Error uploading file.");
    }

    $stmt->bind_param("sssssss", $user_id, $model, $manufacturer, $year, $price, $image, $km);

    $stmt->execute();

    echo "New record created successfully";

    unset($_FILES['car_image']);

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Car Upload Form</title>
    <link rel="stylesheet" href="styles/addNewCar.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        $(function () {
            $("#header").load("navbar.php");
        });
    </script>
</head>

<body>
    <div id="header"></div>

    <h2>Add a New Car</h2>
    <form id="carForm" action="create.php" method="post" enctype="multipart/form-data">
        <label for="carModel">Car Model:</label>
        <input type="text" id="carModel" name="car_model" required>

        <label for="manufacturer">Manufacturer:</label>
        <input type="text" id="manufacturer" name="car_manufacturer" required>

        <label for="year">Year:</label>
        <input type="number" id="year" name="car_year" required>

        <label for="price">Price:</label>
        <input type="text" id="price" name="car_price" required>

        <label for="km">Km:</label>
        <input type="number" id="km" name="car_km" required>

        <label for="carImage">Car Image:</label>
        <input type="file" id="carImage" name="car_image" required>

        <input type="submit" value="Submit">
    </form>
    <div id="footer"></div>
</body>

</html>
