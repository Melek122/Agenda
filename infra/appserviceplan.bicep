@description('Location for resources')
param location string = resourceGroup().location

resource appServicePlan 'Microsoft.Web/serverfarms@2021-02-01' = {
  name: 'ASP-agendamysql-80a4'
  location: location
  sku: {
    name: 'B1'
    tier: 'Basic'
    capacity: 1
  }
}
