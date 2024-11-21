@description('Name of the App Service Plan')
param appServicePlanName string = 'ASP-agendamysql-80a4'

@description('Name of the Web App')
param webAppName string = 'agenda-app'

@description('Location for the Web App')
param location string = resourceGroup().location

resource webApp 'Microsoft.Web/sites@2021-02-01' = {
  name: webAppName
  location: location
  properties: {
    serverFarmId: resourceId('Microsoft.Web/serverfarms', appServicePlanName)
  }
}
