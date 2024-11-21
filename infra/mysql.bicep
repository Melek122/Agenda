@description('The location for resources')
param location string = resourceGroup().location

// Define the MySQL server resource using Flexible Server
resource mysqlServer 'Microsoft.DBforMySQL/flexibleServers@2022-09-01' = {
  name: 'agenda-server'  // Flexible server name (simplified to avoid ".mysql.database.azure.com")
  location: location
  sku: {
    name: 'Standard_B1ms'  // Updated SKU name for Flexible Server
    tier: 'Burstable'  // Burstable workloads tier
  }
  properties: {
    administratorLogin: 'yokasraoui'
    administratorLoginPassword: 'fuckilyes123+'  // Use a secure password in production
    version: '8.0'  // MySQL version
    storage: {
      storageSizeGB: 20  // Specify storage size in GB
    }
    backup: {
      backupRetentionDays: 7  // Backup retention in days
      geoRedundantBackup: 'Disabled'  // Geo-redundant backup
    }
    highAvailability: {
      mode: 'Disabled'  // High availability configuration
    }
  }
}

// Define the MySQL database resource under the Flexible Server
resource mysqlDatabase 'Microsoft.DBforMySQL/flexibleServers/databases@2022-09-01' = {
  parent: mysqlServer  // Use 'parent' to reference the Flexible Server
  name: 'agenda'  // Database name
  properties: {
    collation: 'utf8_general_ci'
    charset: 'utf8'
  }
}
