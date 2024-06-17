<?php
session_start();

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

    // Prepare and bind the INSERT statement
    $stmt = $conn->prepare("INSERT INTO Tickets (user_id, user_email, content, status, user_name, problem) VALUES (?, ?, ?, ?, ?, ?)");

    if (!$stmt) {
        die("Error in SQL query: " . $conn->error);
    }

    // Assign values before binding
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    $user_email = isset($_POST['email']) ? $_POST['email'] : null; // Retrieve email from $_POST
    $content = $_POST['text']; // Assuming the textarea in the form is named 'text'
    $status = "Open"; // Default status for new tickets
    $user_name = isset($_POST['name']) ? $_POST['name'] : null; // Retrieve name from $_POST
    $problem = isset($_POST['problem']) ? $_POST['problem'] : null; // Retrieve problem from $_POST

    // Bind parameters to the statement
    $stmt->bind_param("isssss", $user_id, $user_email, $content, $status, $user_name, $problem);

    // Execute the statement
    $stmt->execute();

    // Check if the insertion was successful
    if ($stmt->affected_rows > 0) {
        echo "Ticket submitted successfully!";
    } else {
        echo "Error submitting ticket.";
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact</title>
    <link rel="stylesheet" href="styles/contact.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        $(function () {
            $("#header").load("navbar.php");
        });
    </script>
</head>
<body>
    <div id="header"></div>
    <h1 id="ContactText">Contact</h1>
    <div id="form-main">
        <div id="form-div">
        <form class="form" id="form1" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            
            <?php
             
                    // User is not logged in, display name and email fields
                    echo '<p class="name">';
                    echo '<input name="name" type="text" class="validate[required,custom[onlyLetter],length[0,100]] feedback-input" placeholder="Name" id="name" />';
                    echo '</p>';
                    echo '<p class="email">';
                    echo '<input name="email" type="text" class="validate[required,custom[email]] feedback-input" id="email" placeholder="Email" />';
                    echo '<input name="problem" type="text" class="validate[required,custom[onlyLetter]] feedback-input" id="email" placeholder="Problem" />';
                    echo '</p>';
                    echo '<p class="text">';
                    echo '<textarea name="text" class="validate[required,length[6,300]] feedback-input" id="comment" placeholder="Comment"></textarea>';
                    echo '</p>';
                
            ?>
            
            <div class="submit">
              <input type="submit" value="SEND" id="button-blue"/>
              <div class="ease"></div>
            </div>
          </form>
        </div>
    </div>
</body>
</html>
