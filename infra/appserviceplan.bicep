resource appServicePlan 'Microsoft.Web/serverfarms@2021-02-01' = {
  name: 'ASP-agendamysql-80a4 (B1: 1)'  // Name of the App Service Plan
  location: resourceGroup().location
  sku: {
    name: 'B1'  // Basic pricing tier, you can change it based on your needs (e.g., S1 for Standard)
    tier: 'Basic'
    capacity: 1  // Scale to 1 instance, can be adjusted
  }
  properties: {
    name: 'myAppServicePlan'
  }
}
