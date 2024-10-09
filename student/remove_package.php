<?php
// Include database connection details
include("db_conn.php");

// Set the header to return JSON
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $packageId = $_POST['id'];

    // Validate the package ID (ensure it's a valid integer)
    if (!filter_var($packageId, FILTER_VALIDATE_INT)) {
        echo json_encode(["success" => false, "message" => "Invalid package ID."]);
        exit;
    }

    try {
        // Prepare the SQL statement to delete the package
        $sqlDelete = "DELETE FROM approved WHERE id = :id";
        $stmtDelete = $conn->prepare($sqlDelete);
        $stmtDelete->execute([':id' => $packageId]);

        // Check if the package was successfully deleted
        if ($stmtDelete->rowCount() > 0) {
            echo json_encode(["success" => true, "message" => "Package removed successfully."]);
        } else {
            echo json_encode(["success" => false, "message" => "Error removing package."]);
        }
    } catch (Exception $e) {
        // Handle any exceptions
        echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request."]);
}
?>
