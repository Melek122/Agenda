resource appServicePlan 'Microsoft.Web/serverfarms@2021-02-01' = {
  name: 'ASP-agendamysql-80a4'  // Fixed by removing trailing space
  location: resourceGroup().location
  sku: {
    name: 'B1'  // Pricing tier
    tier: 'Basic'
    capacity: 1
  }
  properties: {
    perSiteScaling: false
    reserved: false
  }
}
