<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bb_biler";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

if (!$user_id) {
    die("User not logged in.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['profile_image'])) {
    $image = ''; 
    $uploadDir = 'pfp/';
    $uploadFile = $uploadDir . basename($_FILES['profile_image']['name']);

    if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $uploadFile)) {
        $image = $_FILES['profile_image']['name'];
        echo "File is valid, and was successfully uploaded.";
    } else {
        die("Error uploading file.");
    }

    // Update the profile picture in the database
    $updateProfilePic = $conn->prepare("UPDATE users SET profile_picture = ? WHERE user_id = ?");
    $updateProfilePic->bind_param("si", $image, $user_id);
    
    if (!$updateProfilePic->execute()) {
        die("Error updating profile picture: " . $conn->error);
    }

    $updateProfilePic->close();
}

$sql = "SELECT * FROM users WHERE user_id = $user_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $username = $row['username']; 
    $email = $row['email']; 
    $profit = $row['sold_for'];
} else {
    die("User not found.");
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="styles/profile.css">
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
<div class="profile-container">
    <h1>Welcome, <?php echo $username; ?></h1>
    <div class="profile-details">
        <div class="profile-picture">
        <img src="pfp/<?php echo $row['profile_picture']; ?>" alt="Profile Picture">
        </div>
        <div class="profile-info">
            <p>Email: <?php echo $email; ?></p>
            <p>Username: <?php echo $username; ?></p>
            <p>Sales in $: <?php echo $profit; ?></p>
        </div>
    </div>
    <form id="pfpForm" action="profile.php" method="post" enctype="multipart/form-data">
        <div class="profile-actions">
            <!-- Add profile action buttons here -->
            <label for="pfp_Image">Choose profile picture</label>
            <input type="file" id="pfp_Image" name="profile_image">
            <button type="submit">Upload profile picture</button>
        </div>
    </form>
</div>
<div id="footer"></div>
</body>
</html>



