resource mysqlServer 'Microsoft.DBforMySQL/servers@2021-05-01' = {
  name: 'agenda-server.mysql.database.azure.com'  // Your MySQL server name
  location: resourceGroup().location
  properties: {
    administratorLogin: 'cnvjhyjscs'  // MySQL admin username
    administratorLoginPassword: 'fuckilyes123+'  // MySQL admin password
    version: '8.0'  // MySQL version
    sslEnforcement: 'Enabled'  // SSL enforcement enabled
    storageProfile: {
      storageMB: 21474,8  // 5 GB storage, can be adjusted
      backupRetentionDays: 7  // Retention of backups for 7 days
      geoRedundantBackup: 'Disabled'  // Backup configuration, can be adjusted
    }
  }
}

resource mysqlDatabase 'Microsoft.DBforMySQL/servers/databases@2021-05-01' = {
  name: '${agenda-server.mysql.database.azure.com}/agenda_db'  // MySQL database name
  properties: {}
}
