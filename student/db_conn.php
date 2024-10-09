<?php 

$sName = "localhost";
$uName = "root";
$pass = "";
$db_name = "smartptli"; // Database name

try {
    // Create PDO instance
    $conn = new PDO("mysql:host=$sName;dbname=$db_name", $uName, $pass);
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
}
?>
