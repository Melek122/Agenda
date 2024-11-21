resource webApp 'Microsoft.Web/sites@2021-02-01' = {
  name: 'agenda-app'  // Your Azure Web App name
  location: resourceGroup().location
  properties: {
    serverFarmId: appServicePlan.id  // Link to the App Service Plan created above
  }
  identity: {
    type: 'SystemAssigned'  // Managed identity for the app
  }
}
