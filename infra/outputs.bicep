output webAppUrl string = webApp.properties.defaultHostName
output mysqlServerName string = mysqlServer.name
output mysqlDatabaseName string = mysqlDatabase.name
output mysqlConnectionString string = 'mysql://${mysqlServer.name}.mysql.database.azure.com:3306/${mysqlDatabase.name}?ssl-ca=DigiCertGlobalRootCA.crt.pem'
