<?php
// db.php for Azure MySQL Database

// Fetch database connection details from environment variables
$host = getenv('DB_HOST') ?: 'agenda-app-server.mysql.database.azure.com';
$dbname = getenv('DB_NAME') ?: 'agenda-app-database';
$user = getenv('DB_USER') ?: 'ddhquucrom';
$pass = getenv('DB_PASS') ?: 'Test123+';

try {
    // Initialize PDO with the database connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Output an error message if the connection fails
    die("Database connection failed: " . $e->getMessage());
}
?>
