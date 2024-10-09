<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "smartptli";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Set charset to UTF-8 for handling special characters
$conn->set_charset("utf8");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input data
    $fullName = $conn->real_escape_string(trim($_POST['fullName']));
    $mobileNo = $conn->real_escape_string(trim($_POST['mobileNo']));
    $email = $conn->real_escape_string(trim($_POST['email']));
    $message = $conn->real_escape_string(trim($_POST['message']));

    // Prepare and bind the SQL statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO enquiries (full_name, mobile_no, email, message) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $fullName, $mobileNo, $email, $message);

    // Execute the statement
    $stmt->execute();

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
