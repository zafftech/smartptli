<?php
session_start();
include($_SERVER['DOCUMENT_ROOT'] . "/MyWebsite 2.0/Login/connect.php");

// Check if the user is an admin
if ($_SESSION['role'] != 'admin') {
    header("Location: ../MAIN INTERFACE/index.html");
    exit();
}

// Delete user record
if (isset($_GET['id'])) {
    $userId = $_GET['id'];
    
    $sql = "DELETE FROM loginn WHERE Id='$userId'";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: userrole.php");
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    echo "No user ID specified!";
}
?>
