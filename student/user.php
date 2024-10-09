<?php

function getUserById($id, $conn) {
    // Prepare SQL statement with a placeholder
    $stmt = $conn->prepare("SELECT * FROM loginn WHERE Id = ?");
    
    // Execute the statement with the provided ID
    $stmt->execute([$id]);
    
    // Fetch the result as an associative array
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

?>
