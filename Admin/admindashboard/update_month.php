<?php
include("config.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Use lowercase 'id' to match the form field name
    $id = intval($_POST['id']); 
    $month = $_POST['month'];

    try {
        // Update the month in the database using PDO
        $updateQuery = $conn->prepare("UPDATE student_status SET month = ? WHERE id = ?");
        // Execute with parameters
        if ($updateQuery->execute([$month, $id])) {
            // Redirect back to the student status page on success
            header("Location: studentstatus.php");
            exit();
        } else {
            echo "Error updating record.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
    