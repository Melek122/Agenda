resource webApp 'Microsoft.Web/sites@2021-02-01' = {
  name: 'agenda-app'
  location: resourceGroup().location
  properties: {
    serverFarmId: resourceId('Microsoft.Web/serverfarms', 'ASP-agendamysql-80a4')
  }
}
