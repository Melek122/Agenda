<?php
// MySQLi connection with SSL, but without certificate verification

// Database connection details
$host = 'agenda-app-server.mysql.database.azure.com';
$user = 'ddhquucrom';
$pass = 'Test123+';
$dbname = 'agenda_app';

// Initialize MySQLi
$con = mysqli_init();

// Use SSL, but skip server certificate verification
mysqli_ssl_set($con, NULL, NULL, NULL, NULL, NULL);

// Attempt to connect using SSL
if (!mysqli_real_connect($con, $host, $user, $pass, $dbname, 3306, NULL, MYSQLI_CLIENT_SSL)) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>
