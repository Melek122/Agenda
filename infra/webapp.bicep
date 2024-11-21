resource appServicePlan 'Microsoft.Web/serverfarms@2021-02-01' = {
  name: 'ASP-agendamysql-80a4'  // Name of the App Service Plan
  location: resourceGroup().location
  sku: {
    name: 'B1'  // Basic pricing tier (e.g., Free, B1, S1, P1v2)
    tier: 'Basic'  // Matches the pricing tier
    capacity: 1  // Number of instances
  }
  properties: {
    perSiteScaling: false  // Default scaling for all sites in this plan
    reserved: false  // Default for non-Linux App Service Plans
  }
}
