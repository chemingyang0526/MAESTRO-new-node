#!/usr/bin/env python

import os, MySQLdb, json, sys
from phpserialize import serialize, unserialize
import boto3
from azure.common.credentials import ServicePrincipalCredentials
from azure.mgmt.resource import ResourceManagementClient
from azure.mgmt.storage import StorageManagementClient
from azure.mgmt.network import NetworkManagementClient
from azure.mgmt.compute import ComputeManagementClient
from haikunator import Haikunator

class cloudVirtualMachine:
    
    def __init__(self):
        self.author = "Manish Pandit, HTBase, mpandit@htbase.com"

    def awsConnection(self):
        db = MySQLdb.connect(host="localhost", user="root", passwd="htbase", db="htvcenter")
        cur = db.cursor()
        cur.execute("SELECT * FROM cloud_credential WHERE id = 1")
        row = cur.fetchone()
        unserializedData = unserialize(row[2])
        awsaccesskeyid = unserializedData['aws_access_key_id']
        awssecretaccesskey = unserializedData['aws_secret_access_key']
        session = boto3.Session(aws_access_key_id=awsaccesskeyid, aws_secret_access_key=awssecretaccesskey, region_name='us-east-1')
        s3 = boto3.resource('s3', aws_access_key_id = awsaccesskeyid, aws_secret_access_key = awssecretaccesskey, region_name='us-east-1')
        client = boto3.resource('ec2', aws_access_key_id=awsaccesskeyid, aws_secret_access_key=awssecretaccesskey, region_name='us-east-1')
        ec2 = session.resource('ec2', region_name='us-east-1')
        return ec2

    def awsInstanceList(self):
        client = self.awsConnection()
        instances = client.instances.filter()
        return instances
    
    def azureConnection(self):
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
        return compute_client
        
    def azureVmList(self):
        compute_client = self.azureConnection()
        az_vms = compute_client.virtual_machines.list_all()
        return az_vms

if __name__ == "__main__":
    if sys.argv[1] == "az":
        vmList = []
        azureVms = cloudVirtualMachine()
        az_vms = azureVms.azureVmList()
        compute_client = azureVms.azureConnection()
        total_count = 0
        running_count = 0
        try:
            for vm in az_vms:
                total_count = total_count + 1
                vmID = vm.id
                resourceGroup = vmID.split("/")[4]
                v_m = compute_client.virtual_machines.get(resourceGroup, vm.name, expand = 'instanceview')
                if v_m.instance_view.statuses[1].display_status == "VM running":
                    running_count = running_count + 1
            print "Total instance: " + str(total_count)
            print "Running instance: " + str(running_count)
        except Exception as e:
            print str(e)
    else:
        instanceList = []
        awsInstances = cloudVirtualMachine()
        availableInstances = awsInstances.awsInstanceList()
        total_count = 0
        running_count = 0
        try:
            for instance in availableInstances:
                if instance.state['Name'] == "running":
                    running_count = running_count + 1
                total_count = total_count + 1
            print "Total instance: " + str(total_count)
            print "Running instance: " + str(running_count)
        except Exception as e:
            print str(e)