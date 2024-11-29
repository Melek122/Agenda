<?php
// db.php for Azure MySQL Database (without SSL)

$host = 'agenda-server.mysql.database.azure.com';  // Fully qualified domain name of your MySQL server
$dbname = 'agenda-database';  // Replace with your actual database name
$user = 'fbwgcxxxjl@agenda-server';  // Azure MySQL username (with @servername)
$pass = 'Test123+';  // Your Azure MySQL password

// DSN (Data Source Name)
$dsn = "mysql:host=$host;dbname=$dbname;port=3306;charset=utf8mb4";

// PDO options
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,  // Throw exceptions on errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,  // Fetch results as associative arrays
];

try {
    // Create a new PDO instance
    $pdo = new PDO($dsn, $user, $pass, $options);

    // Test the connection (optional)
    echo "Connected successfully without SSL!";

} catch (PDOException $e) {
    // If the connection fails, show an error message
    echo "Connection failed: " . $e->getMessage();
    exit();  // Stop script execution on connection failure
}
