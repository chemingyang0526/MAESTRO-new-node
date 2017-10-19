#!/usr/bin/env python

from azure.common.credentials import ServicePrincipalCredentials
from azure.mgmt.resource import ResourceManagementClient
from azure.mgmt.storage import StorageManagementClient
from azure.mgmt.network import NetworkManagementClient
from azure.mgmt.compute import ComputeManagementClient
from haikunator import Haikunator

import os, MySQLdb, json, sys
from phpserialize import serialize, unserialize

db = MySQLdb.connect(host="localhost", user="root", passwd="htbase", db="htvcenter")
cur = db.cursor()
cur.execute("SELECT * FROM cloud_credential WHERE id = 2")
row = cur.fetchone()
unserializedData = unserialize(row[2])

subscription_id = unserializedData['subscription_id']
clientid = unserializedData['client_id']
secretkey = unserializedData['secret_key']
tenantid = unserializedData['tenant_id']

credentials = ServicePrincipalCredentials(client_id=clientid, secret=secretkey, tenant=tenantid)

resource_client = ResourceManagementClient(credentials, subscription_id)
compute_client = ComputeManagementClient(credentials, subscription_id)
storage_client = StorageManagementClient(credentials, subscription_id)
network_client = NetworkManagementClient(credentials, subscription_id)