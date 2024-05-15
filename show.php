<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "visitor_db";

session_start();

// Check if user is logged in, if not, redirect them to the login page
if(!isset($_SESSION['username'])) {
    header("Location: loginpage.php");
    exit;
}
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST["token"];

    // SQL to search for token and match the current date
    $currentDate = date("Y-m-d");
    $sql = "SELECT * FROM visitor_database WHERE Token_Number = '$token' AND date_t = '$currentDate'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Token found
        while ($row = $result->fetch_assoc()) {
            $name = $row["Name"];
            $mobileNumber = $row["Mobile Number"];
            $aadharNumber = $row["Aadhar Number"];
            $address = $row["Address"];
            $photo = $row["Photo"];
            
            // Displaying the record with Bootstrap
            echo "
            <div class='container mt-5'>
                <div class='row'>
                    <div class='col-md-4'>
                        <img src='$photo' alt='Photo' class='img-fluid'>
                    </div>
                    <div class='col-md-8'>
                        <h2>Name: $name</h2>
                        <p>Mobile Number: $mobileNumber</p>
                        <p>Aadhar Number: $aadharNumber</p>
                        <p>Address: $address</p>
                    </div>
                </div>
            </div>
            ";
        }
    } else {
        // Token not found
        echo "Token not found for today's date!";
    }
}

// Logout functionality
if(isset($_POST['logout'])) {
    // Perform logout actions here
    // For example: destroy session, redirect to login page, etc.
    session_destroy();
    header("Location: loginpage.php"); // Redirect to login page
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Record Search</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Record Search</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label for="token">Enter Token Number:</label>
                <input type="text" class="form-control" id="token" name="token" required>
            </div>
            <button type="submit" class="btn btn-primary">Search</button>
        </form>
        <!-- Logout Button -->
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <button type="submit" class="btn btn-danger mt-3" name="logout">Logout</button>
        </form>
    </div>
</body>
</html>
