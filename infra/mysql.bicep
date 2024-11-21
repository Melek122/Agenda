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
      name: 'GP_Gen5_2'  // SKU for Basic tier with Gen5 architecture and 1 vCore
      tier: 'GeneralPurpose'  // Tier selection (Basic, GeneralPurpose, MemoryOptimized)
      capacity: 2  // Capacity in vCores
    }
    storageProfile: {
      storageMB: 20000  // Storage size in MB (e.g., 5GB)
      backupRetentionDays: 7  // Backup retention days (set according to your needs)
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
