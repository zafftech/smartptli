<?php
include('DB.php');

$notifications_name = $_POST["notifications_name"];
$message = $_POST["message"];

// Use prepared statements to prevent SQL injection
$insert_query = $connection->prepare("INSERT INTO notifications (notifications_name, message, active) VALUES (?, ?, 1)");
$insert_query->bind_param("ss", $notifications_name, $message);
$insert_query->execute();

if ($insert_query->error) {
    echo "Error: " . $insert_query->error;
} else {
    echo "Notification inserted.";
}
?>
