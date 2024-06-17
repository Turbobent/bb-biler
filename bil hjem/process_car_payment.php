<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['buy'])) {
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

    $carIdToBuy = $_POST['car_id'];

    // Fetch car details from the database
    $sql = "SELECT car_id, model, price, user_id FROM cars WHERE car_id = $carIdToBuy";
    $result = $conn->query($sql);

    // Check if the query was successful
    if ($result === false) {
        die("Error in SQL query: " . $conn->error);
    }

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Fetch buyer's and seller's funds
        session_start();
        $buyer_id = $_SESSION['user_id'];
        $buyerFunds = $conn->query("SELECT funds FROM users WHERE user_id = $buyer_id")->fetch_assoc()['funds'];

        $seller_id = $row['user_id'];
        $sellerFunds = $conn->query("SELECT funds FROM users WHERE user_id = $seller_id")->fetch_assoc()['funds'];

        // Calculate the amount to transfer to user ID 7 (6% of the total value)
        $transferAmountToUser7 = $row['price'] * 0.06;

        // Check if the buyer has enough funds to buy the car
        if ($buyerFunds >= $row['price']) {
            // Update buyer's funds
            $newBuyerFunds = $buyerFunds - $row['price'];
            $conn->query("UPDATE users SET funds = $newBuyerFunds WHERE user_id = $buyer_id");

            // Update seller's funds and sold_for amount
            $newSellerFunds = $sellerFunds + $row['price'] - $transferAmountToUser7;
            $newSoldFor = $conn->query("SELECT sold_for FROM users WHERE user_id = $seller_id")->fetch_assoc()['sold_for'] + $row['price'];
            $conn->query("UPDATE users SET funds = $newSellerFunds, sold_for = $newSoldFor WHERE user_id = $seller_id");

            // Update user ID 7 funds
            $user7Funds = $conn->query("SELECT funds FROM users WHERE user_id = 7")->fetch_assoc()['funds'];
            $newUser7Funds = $user7Funds + $transferAmountToUser7;
            $conn->query("UPDATE users SET funds = $newUser7Funds WHERE user_id = 7");

            // Update car status (e.g., mark it as sold)
            $conn->query("UPDATE cars SET status = 'Sold' WHERE car_id = $carIdToBuy");
            $conn->query("UPDATE cars SET sold = 1 WHERE car_id = $carIdToBuy");
            echo "Car with ID $carIdToBuy purchased successfully!";
            echo '<script>window.location.href = "index.php";</script>'; // Redirect to the index page after purchase

        } else {
            echo "Insufficient funds to buy the car.";
        }
    } else {
        echo "Car not found.";
    }
    $conn->close();
}

?>

