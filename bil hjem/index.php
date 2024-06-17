<?php
session_start(); // Start the session
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bb_biler";
$recordsPerPage = 10;
 

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
 
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
 
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
 
function getTotalRecords($conn, $user_id) {
    if ($user_id) {
        $stmt = $conn->prepare("SELECT COUNT(*) as total FROM cars WHERE sold = 0 AND user_id != ?");
        $stmt->bind_param("i", $user_id);
    } else {
        $stmt = $conn->prepare("SELECT COUNT(*) as total FROM cars WHERE sold = 0");
    }
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result === false) {
        die("Error in SQL query: " . $conn->error);
    }
    $row = $result->fetch_assoc();
    return $row['total'];
}
 
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $recordsPerPage;

if ($user_id) {
    $stmt = $conn->prepare("SELECT car_id, model, manufacturer, year, price, image, km, user_id FROM cars WHERE user_id != ? AND sold = 0 LIMIT ?, ?");
    $stmt->bind_param("iii", $user_id, $offset, $recordsPerPage);
} else {
    $stmt = $conn->prepare("SELECT car_id, model, manufacturer, year, price, image, km, user_id FROM cars WHERE sold = 0 LIMIT ?, ?");
    $stmt->bind_param("ii", $offset, $recordsPerPage);
}
$stmt->execute();
$result = $stmt->get_result();
 
// Check if the query was successful
if ($result === false) {
    die("Error in SQL query: " . $conn->error);
}
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
<input type="text" id="Search" placeholder="Search...">
<div class="carGrid">
 
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
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
                    if ($user_id) {
                        echo '<form method="post" action="process_car_payment.php">';
                        echo '<input type="hidden" name="car_id" value="' . $row["car_id"] . '">';
                        echo '<button type="submit" name="buy">Buy</button>';
                        echo '</form>';
                         echo '<a href="report.php?car_id=' . $row["car_id"] . '&reported_user_id=' . $row["user_id"] . '">Report</a>'; 
                    }
                    echo '</div>';
                }
            } else {
                echo "0 results";
            }
            ?>
 
        </div>
 
<?php
            // Pagination links
            $totalRecords = getTotalRecords($conn, $user_id);
            $totalPages = ceil($totalRecords / $recordsPerPage);
 
            if ($totalPages > 1) {
                for ($i = 1; $i <= $totalPages; $i++) {
                    echo '<a href="?page=' . $i . '">' . $i . '</a> ';
                }
            }
            $conn->close();
            ?>
</div>
</body>
 
</html>
