<?php
// db.php for Azure MySQL Database (without SSL)

$host = 'agenda-app-server.mysql.database.azure.com';  // Fully qualified domain name of your MySQL server
$dbname = 'agenda-app-database';  
$user = 'ddhquucrom';  // Include @servername for Azure MySQL username
$pass = 'Test123+';  // Your Azure MySQL password

// DSN (Data Source Name)
$dsn = "mysql:host=$host;dbname=$dbname;port=3306;charset=utf8mb4";

