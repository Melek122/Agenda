<?php
// MySQLi connection with SSL

// Database connection details
$host = 'agenda-app-server.mysql.database.azure.com';
$user = 'ddhquucrom';
$pass = 'Test123+';
$dbname = 'agenda-app-database';
$sslCertPath = '/home/site/wwwroot/DigiCertGlobalRootCA.crt.pem'; // Path to SSL cert

// Initialize MySQLi
$con = mysqli_init();
mysqli_ssl_set($con, NULL, NULL, $sslCertPath, NULL, NULL);

// Attempt to connect
if (!mysqli_real_connect($con, $host, $user, $pass, $dbname, 3306, NULL, MYSQLI_CLIENT_SSL)) {
    die("Database connection failed: " . mysqli_connect_error());
} else {
    echo "Database connection successful!";
}
?>
