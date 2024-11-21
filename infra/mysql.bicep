@description('The location for resources')
param location string = resourceGroup().location


// Define the MySQL server resource
resource mysqlServer 'Microsoft.DBforMySQL/servers@2020-01-01' = {
  name: 'agenda-server.mysql.database.azure.com'
  location: location
  properties: {
    administratorLogin: 'yokasraoui'
    administratorLoginPassword: 'fuckilyes123+'  // Use secure password
    version: '8.0'
    sku: {
      name: 'B1ms'  // Burstable SKU with 1 vCore and 2 GiB RAM
      tier: 'Burstable'  // Tier for burstable workloads
      capacity: 1  // 1 vCore
    }
    storageProfile: {
      storageMB: 20480  // 20 GiB storage
      backupRetentionDays: 7  // Backup retention days
      geoRedundantBackup: 'Disabled'  // Enable or disable geo-redundant backup
    }
  }
}

// Define the MySQL database resource under the MySQL server
resource mysqlDatabase 'Microsoft.DBforMySQL/servers/databases@2020-01-01' = {
  parent: mysqlServer  // Use 'parent' to reference the MySQL server
  name: 'agenda-server'  // Database name
  properties: {
    collation: 'utf8_general_ci'
    charset: 'utf8'
  }
}
