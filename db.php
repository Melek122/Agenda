<?php
// MySQLi connection with SSL, but without certificate verification

// Database connection details
$host = 'agenda-app-server.mysql.database.azure.com';
$user = 'ddhquucrom';
$pass = 'Test123+';
$dbname = 'agenda-app-database';

// Initialize MySQLi
$con = mysqli_init();

// Use SSL, but skip server certificate verification
mysqli_ssl_set($con, NULL, NULL, NULL, NULL, NULL);

// Attempt to connect using SSL
if (!mysqli_real_connect($con, $host, $user, $pass, $dbname, 3306, NULL, MYSQLI_CLIENT_SSL)) {
    die("Database connection failed: " . mysqli_connect_error());
} else {
    echo "Database connection successful!";
}
try {
    // Create the PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    
    // Set PDO attributes
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>
