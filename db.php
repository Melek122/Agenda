<?php
// Database connection details
$host = 'agenda-app-server.mysql.database.azure.com';
$user = 'ddhquucrom';
$pass = 'Test123+';
$dbname = 'agenda-app-database';

try {
    // Create the PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    
    // Set PDO attributes
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  // Enable error handling with exceptions
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);  // Set the default fetch mode to associative arrays
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());  // If there is an error, terminate and print the error message
}
?>
