<?php
// db.php for Azure SQL Database

$host = 'agenda-server.mysql.database.azure.com';  // Azure SQL Server name (fully qualified domain name)
$dbname = 'agenda-server';  // Name of your Azure SQL Database
$user = 'cnvjhyjscs';  // Your Azure SQL Database username
$pass = 'fuckilyes123+';  // Your Azure SQL Database password

try {
    // Create a PDO connection to Azure SQL Database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected to Azure SQL Database!";
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
