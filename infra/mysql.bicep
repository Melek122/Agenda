@description('The location for resources')
param location string = resourceGroup().location

@description('MySQL server name')
param agenda-server string

resource mysqlServer 'Microsoft.DBforMySQL/servers@2021-05-01' = {
  name: agenda-server
  location: location
  properties: {
    administratorLogin: 'yokasraoui'
    administratorLoginPassword: 'fuckilyes123+'
    version: '8.0'
  }
}

resource mysqlDatabase 'Microsoft.DBforMySQL/servers/databases@2021-05-01' = {
  name: '${mysqlServer.name}/agenda'
  properties: {
    collation: 'utf8_general_ci'
    charset: 'utf8'
  }
}
