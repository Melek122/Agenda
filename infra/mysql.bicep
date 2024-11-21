@description('The location for resources')
param location string = resourceGroup().location

@description('MySQL server name')
param mysqlServerName string

resource mysqlServer 'Microsoft.DBforMySQL/servers@2021-05-01' = {
  name: mysqlServerName
  location: location
  properties: {
    administratorLogin: 'admin'
    administratorLoginPassword: 'Password123!'
    version: '5.7'
  }
}

resource mysqlDatabase 'Microsoft.DBforMySQL/servers/databases@2021-05-01' = {
  name: '${mysqlServer.name}/agenda'
  properties: {
    collation: 'utf8_general_ci'
    charset: 'utf8'
  }
}
