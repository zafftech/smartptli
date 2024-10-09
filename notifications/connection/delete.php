<?php
include('DB.php');

$notification_id = $_POST["id"]; // Get the ID of the notification to delete

// Use prepared statements to prevent SQL injection
$delete_query = $connection->prepare("DELETE FROM notifications WHERE n_id = ?");
$delete_query->bind_param("i", $notification_id);
$delete_query->execute();

if ($delete_query->error) {
    echo "Error: " . $delete_query->error;
} else {
    echo "Notification deleted.";
}
?>
