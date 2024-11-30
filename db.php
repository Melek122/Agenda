<?php
// MySQLi connection without SSL verification

// Database connection details
$host = 'agenda-app-server.mysql.database.azure.com';
$user = 'ddhquucrom';
$pass = 'Test123+';
$dbname = 'agenda-app-database';

// Initialize MySQLi
$con = mysqli_init();

// Skip SSL certificate verification
mysqli_ssl_set($con, NULL, NULL, NULL, NULL, NULL);

// Attempt to connect without verifying the server certificate
if (!mysqli_real_connect($con, $host, $user, $pass, $dbname, 3306, NULL, MYSQLI_CLIENT_SSL_DONT_VERIFY_SERVER_CERT)) {
    die("Database connection failed: " . mysqli_connect_error());
} else {
    echo "Database connection successful!";
}
?>
