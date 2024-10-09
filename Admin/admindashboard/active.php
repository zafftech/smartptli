<?php 
include("config.php");

// Ensure Id and status are safely retrieved
$id = intval($_GET['id']);
$status = intval($_GET['status']);

try {
    // Prepared statement using PDO to prevent SQL injection
    $updatequery1 = $conn->prepare("UPDATE student_status SET status = ? WHERE id = ?");
    $updatequery1->execute([$status, $id]);

    // Redirect to studentstatus.php after successful update
    header('Location: studentstatus.php');
    exit();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit();
}
?>
