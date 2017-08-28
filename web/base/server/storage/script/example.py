import os
from haikunator import Haikunator
from azure.common.credentials import ServicePrincipalCredentials
from azure.mgmt.resource import ResourceManagementClient
from azure.mgmt.storage import StorageManagementClient
from azure.mgmt.storage.models import (
    StorageAccountCreateParameters,
    StorageAccountUpdateParameters,
    Sku,
    SkuName,
    Kind
)

WEST_US = 'westus'
GROUP_NAME = 'htbaseresource'
STORAGE_ACCOUNT_NAME = Haikunator().haikunate(delimiter='')

# This script expects that the following environment vars are set:
#
# AZURE_TENANT_ID: with your Azure Active Directory tenant id or domain
# AZURE_CLIENT_ID: with your Azure Active Directory Application Client ID
# AZURE_CLIENT_SECRET: with your Azure Active Directory Application Secret
# AZURE_SUBSCRIPTION_ID: with your Azure Subscription Id
#
def run_example():
    """Storage management example."""
    #
    # Create the Resource Manager Client with an Application (service principal) token provider
    #
    subscription_id = os.environ.get(
        'AZURE_SUBSCRIPTION_ID',
        'dd19825f-8f3d-4be8-9851-9f49a268dadb') # your Azure Subscription Id
        #nHdbzQ3eAo3tL5PFk2iB2TC Application Password
        #353c5bcb-3f48-438f-a4f9-3ab751065cda Application ID
    credentials = ServicePrincipalCredentials(
        client_id=os.environ['updatedmanish@live.com'],
        secret=os.environ['CrackCoke@LIVE7887'],
        tenant=os.environ['1df86eff-830c-4778-ba7a-1c1bd1d4a01a']
    )
    resource_client = ResourceManagementClient(credentials, subscription_id)
    storage_client = StorageManagementClient(credentials, subscription_id)

    # You MIGHT need to add Storage as a valid provider for these credentials
    # If so, this operation has to be done only once for each credentials
    resource_client.providers.register('Microsoft.Storage')

    # Create Resource group
    print('Create Resource Group')
    resource_group_params = {'location':'westus'}
    print_item(resource_client.resource_groups.create_or_update(GROUP_NAME, resource_group_params))

    # Check availability
    print('Check name availability')
    bad_account_name = 'invalid-or-used-name'
    availability = storage_client.storage_accounts.check_name_availability(bad_account_name)
    print('The account {} is available: {}'.format(bad_account_name, availability.name_available))
    print('Reason: {}'.format(availability.reason))
    print('Detailed message: {}'.format(availability.message))
    print('\n\n')

    # Create a storage account
    print('Create a storage account')
    storage_async_operation = storage_client.storage_accounts.create(
        GROUP_NAME,
        STORAGE_ACCOUNT_NAME,
        StorageAccountCreateParameters(
            sku=Sku(SkuName.standard_ragrs),
            kind=Kind.storage,
            location='westus'
        )
    )
    storage_account = storage_async_operation.result()
    print_item(storage_account)
    print('\n\n')

    # Get storage account properties
    print('Get storage account properties')
    storage_account = storage_client.storage_accounts.get_properties(
        GROUP_NAME, STORAGE_ACCOUNT_NAME)
    print_item(storage_account)
    print("\n\n")

    # List Storage accounts
    print('List storage accounts')
    for item in storage_client.storage_accounts.list():
        print_item(item)
    print("\n\n")

    # List Storage accounts by resource group
    print('List storage accounts by resource group')
    for item in storage_client.storage_accounts.list_by_resource_group(GROUP_NAME):
        print_item(item)
    print("\n\n")

    # Get the account keys
    print('Get the account keys')
    storage_keys = storage_client.storage_accounts.list_keys(GROUP_NAME, STORAGE_ACCOUNT_NAME)
    storage_keys = {v.key_name: v.value for v in storage_keys.keys}
    print('\tKey 1: {}'.format(storage_keys['key1']))
    print('\tKey 2: {}'.format(storage_keys['key2']))
    print("\n\n")

    # Regenerate the account key 1
    print('Regenerate the account key 1')
    storage_keys = storage_client.storage_accounts.regenerate_key(
        GROUP_NAME,
        STORAGE_ACCOUNT_NAME,
        'key1')
    storage_keys = {v.key_name: v.value for v in storage_keys.keys}
    print('\tNew key 1: {}'.format(storage_keys['key1']))
    print("\n\n")

    # Update storage account
    print('Update storage account')
    storage_account = storage_client.storage_accounts.update(
        GROUP_NAME, STORAGE_ACCOUNT_NAME,
        StorageAccountUpdateParameters(
            sku=Sku(SkuName.standard_grs)
        )
    )
    print_item(storage_account)
    print("\n\n")

    # Delete the storage account
    print('Delete the storage account')
    storage_client.storage_accounts.delete(GROUP_NAME, STORAGE_ACCOUNT_NAME)
    print("\n\n")

    # Delete Resource group and everything in it
    print('Delete Resource Group')
    delete_async_operation = resource_client.resource_groups.delete(GROUP_NAME)
    delete_async_operation.wait()
    print("Deleted: {}".format(GROUP_NAME))
    print("\n\n")

    # List usage
    print('List usage')
    for usage in storage_client.usage.list().value:
        print('\t{}'.format(usage.name.value))

def print_item(group):
    """Print an Azure object instance."""
    print("\tName: {}".format(group.name))
    print("\tId: {}".format(group.id))
    print("\tLocation: {}".format(group.location))
    print("\tTags: {}".format(group.tags))
    if hasattr(group, 'properties'):
        print_properties(group.properties)

def print_properties(props):
    """Print a ResourceGroup properties instance."""
    if props and props.provisioning_state:
        print("\tProperties:")
        print("\t\tProvisioning State: {}".format(props.provisioning_state))
    print("\n\n")

if __name__ == "__main__":
    run_example()