@description('The location for resources')
param location string = resourceGroup().location

@description('MySQL server name')
param agenda-server.mysql.database.azure.com string  // Use camelCase for parameter names

// Define the MySQL server resource
resource mysqlServer 'Microsoft.DBforMySQL/servers@2021-05-01' = {
  name: agenda-server.mysql.database.azure.com
  location: location
  properties: {
    administratorLogin: 'yokasraoui'
    administratorLoginPassword: fuckilyes123+  // Use secure password
    version: '8.0'
  }
}

// Define the MySQL database resource under the MySQL server
resource mysqlDatabase 'Microsoft.DBforMySQL/servers/databases@2021-05-01' = {
  parent: mysqlServer  // Use 'parent' to reference the MySQL server
  name: 'agenda-server'  // Database name
  properties: {
    collation: 'utf8_general_ci'
    charset: 'utf8'
  }
}
