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

// Check if user is logged in
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Query to select cars uploaded by the logged-in user
    $sql = "SELECT car_id, model, manufacturer, year, price, image, km FROM cars WHERE sold = 0 AND user_id = ?";
    
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Error in SQL query: " . $conn->error);
    }

    $stmt->bind_param("s", $user_id);
    $stmt->execute();

    $result = $stmt->get_result();

    $stmt->close();
} else {
    $result = null;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>bb biler</title>
    <link rel="stylesheet" href="styles/index.css">
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
    <div class="carcontainer">
        <div class="carGrid">
            <?php
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // Display each car's details here
                    echo '<div class="carCards">';
                    echo '<div class="carPictureContainer">';
                    echo '<img src="/uploads/' . $row["image"] . '" alt="' . $row["model"] . '" class="carPicture">';
                    echo '</div>';
                    echo '<div class="carInfo">';
                    echo '<h4>' . $row["model"] . '</h4>';
                    echo '<p>Manufacturer: ' . $row["manufacturer"] . '</p>';
                    echo '<p>Year: ' . $row["year"] . '</p>';
                    echo '<p>Price: $' . $row["price"] . '</p>';
                    echo '<p>KM: ' . $row["km"] . '</p>';
                    echo '</div>';
                    echo '<a href="editcarinfo.php?car_id=' . $row["car_id"] . '">Edit car</a>';
                    echo '<a href="deletecar.php?car_id=' . $row["car_id"] . '" class="deleteButton">Delete Car</a>';
                    echo '</div>';
                }
            } else {
                echo "0 results";
            }
            ?>
        </div>
    </div>
    <div id="footer"></div>
</body>

</html>
