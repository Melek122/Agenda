<?php
// db.php with SSL

// Fetch database connection details from environment variables or hardcoded values for testing
$host = getenv('DB_HOST') ?: 'agenda-app-akfhgecxbdecc7a8.northeurope-01.azurewebsites.net';
$dbname = getenv('DB_NAME') ?: 'agenda-app-database';
$user = getenv('DB_USER') ?: 'ddhquucrom';
$pass = getenv('DB_PASS') ?: 'Test123+';

// Path to the SSL certificate
$sslCertPath = '/home/site/wwwroot/DigiCertGlobalRootG2.crt.pem'; // Adjust to the actual location on your server

// PDO options, including SSL configuration
$options = array(
    PDO::MYSQL_ATTR_SSL_CA => $sslCertPath,
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Enable exception mode for errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC // Optional: set default fetch mode
);

try {
    // Initialize the PDO connection
    $pdo = new PDO("mysql:host=$host;port=3306;dbname=$dbname;charset=utf8mb4", $user, $pass, $options);
    echo "Database connection successful!";
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
