<?php
session_start();
include($_SERVER['DOCUMENT_ROOT'] . "/MyWebsite 2.0/Login/connect.php");

// Check if the user is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../MAIN INTERFACE/index.html");
    exit();
}

// Update user information
if (isset($_POST['id'])) {
    $userId = $_POST['id'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    
    // Prepare an update statement
    $stmt = $conn->prepare("UPDATE loginn SET firstName=?, lastName=?, email=?, role=? WHERE Id=?");
    $stmt->bind_param("ssssi", $firstName, $lastName, $email, $role, $userId);
    
    if ($stmt->execute()) {
        header("Location: userrole.php");
    } else {
        echo "Error: " . $stmt->error;
    }
    
    $stmt->close();
} else {
    echo "No user ID specified!";
}

$conn->close();
?>
