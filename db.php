<?php
// db.php for Azure MySQL Database

$host = 'agenda-server.mysql.database.azure.com';  // Fully qualified domain name of your MySQL server
$dbname = 'agenda-server';  // Replace with your actual database name
$user = 'cnvjhyjscs';  // Include @servername for Azure MySQL username
$pass = 'fuckilyes123+';  // Your Azure MySQL password
$ssl_cert = 'DigiCertGlobalRootCA.crt.pem';  // Path to the downloaded SSL certificate

try {
    // Create a PDO connection with SSL
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4;ssl-ca=$ssl_cert",
        $user,
        $pass
    );

    // Set error mode to exceptions
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Connected to Azure MySQL Database with SSL!";
} catch (PDOException $e) {
    // Handle connection errors
    die("Database connection failed: " . $e->getMessage());
}
?>
