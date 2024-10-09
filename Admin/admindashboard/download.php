<?php
// Check if the file exists
if (isset($_GET['file'])) {
    $filePath = $_GET['file'];

    // Sanitize the file path
    $filePath = str_replace('..', '', $filePath); // Prevent directory traversal

    // Define the full path to the file
    $fullFilePath = __DIR__ . '/' . $filePath;

    // Check if the file exists
    if (file_exists($fullFilePath)) {
        // Get the file's extension
        $fileInfo = pathinfo($fullFilePath);
        $extension = strtolower($fileInfo['extension']);

        // Set headers to force download
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($fullFilePath) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($fullFilePath));

        // Read the file and output its contents
        readfile($fullFilePath);
        exit;
    } else {
        echo "File not found.";
    }
} else {
    echo "No file specified.";
}
?>
