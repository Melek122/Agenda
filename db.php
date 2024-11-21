<?php
// db.php for Azure SQL Database

$host = 'agenda-server.mysql.database.azure.com';  // Azure SQL Server name (fully qualified domain name)
$dbname = 'agenda-server';  // Name of your Azure SQL Database
$user = 'cnvjhyjscs';  // Your Azure SQL Database username
$pass = 'fuckilyes123+';  // Your Azure SQL Database password
$ssl_cert = 'DigiCertGlobalRootCA.crt.pem';  // Path to the downloaded SSL certificate


try {
    // Create a PDO connection to Azure MySQL Database with SSL
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;ssl-ca=$ssl_cert",
        $user,
        $pass
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected to Azure MySQL Database with SSL!";
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
