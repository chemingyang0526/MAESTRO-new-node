#!/usr/bin/env python

import os, MySQLdb, json, sys
import boto3
from phpserialize import serialize, unserialize

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

    def awsVolumeList(self):
        client = self.awsConnection()
        awsVolumes = []
        for v in client.volumes.all():
            awsVolumes.append(str(v.id) + "_*_" + str(v.size) + "_*_" + str(v.state) + "_*_" + str(v.volume_type))
        return awsVolumes

    def awsInstanceList(self):
        client = self.awsConnection()
        instances = client.instances.filter()
        return instances
        
    def awsCpuRam(self, size):
        awsInstanceSizes = ["t1.micro-1-0.613", "t2.nano-1-0.5", "t2.micro-1-1", "t2.small-1-2", "t2.medium-2-4", "t2.large-2-8", "t2.xlarge-4-16", "t2.2xlarge-8-32",
			"m4.large-2-8", "m4.xlarge-4-16", "m4.2xlarge-8-32", "m4.4xlarge-16-64", "m4.10xlarge-40-160", "m4.16xlarge-64-256", "m3.medium-1-3.75", "m3.large-2-7.5", "m3.xlarge-4-15", "m3.2xlarge-8-30", "m1.small-1-1.7", "m1.medium-1-3.7", "m1.large-2-7.5", "m1.xlarge-4-15",
			"c4.large-2-3.75", "c4.xlarge-4-7.5", "c4.2xlarge-8-15", "c4.4xlarge-16-30", "c4.8xlarge-36-60", "c3.large-2-3.75", "c3.xlarge-4-7.5", "c3.2xlarge-8-15", "c3.4xlarge-16-30", "c3.8xlarge-32-60", "c1.medium-2-1.7", "c1.xlarge-8-7", "cc2.8xlarge-32-60.5", "cc1.4xlarge-16-23",
			"f1.2xlarge-8-122", "f1.16xlarge-64-976",
			"g3.4xlarge-16-122", "g3.8xlarge-32-244", "g3.16xlarge-64-488", "g2.2xlarge-8-15", "g2.8xlarge-32-60", "cg1.4xlarge-16-22",
			"p2.xlarge-4-61", "p2.8xlarge-32-488", "p2.16xlarge-64-732",
			"r4.large-2-15.25", "r4.xlarge-4-30.5", "r4.2xlarge-8-61", "r4.4xlarge-16-122", "r4.8xlarge-32-244", "r4.16xlarge-64-488", "r3.large-2-15", "r3.xlarge-4-30.5", "r3.2xlarge-8-61", "r3.4xlarge-16-122", "r3.8xlarge-32-244",
			"x1.16xlarge-64-976", "x1e.32xlarge-128-3904", "x1.32xlarge-128-1952",
			"m2.xlarge-2-17.1", "m2.2xlarge-4-34.2", "m2.4xlarge-8-68.4",
			"cr1.8xlarge-32-244", "d2.xlarge-4-30.5", "d2.2xlarge-8-61", "d2.4xlarge-16-122", "d2.8xlarge-36-244",
			"i2.xlarge-4-30.5", "i2.2xlarge-8-61", "i2.4xlarge-16-122", "i2.8xlarge-32-244", "i3.large-2-15.25", "i3.xlarge-4-30.5", "i3.2xlarge-8-61", "i3.4xlarge-16-122", "i3.8xlarge-32-244", "i3.16xlarge-64-488", "hi1.4xlarge-16-60.5", "hs1.8xlarge-16-117"]
        for items in awsInstanceSizes:
            prop = items.split("-")
            if prop[0] == size:
                return prop[0], prop[1], prop[2]
    
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
    
    def azCpuRam(self, vm_size):
        with open('/usr/share/htvcenter/web/base/server/cloud/script/azurePackages.json') as d:
            az_packages = json.load(d)
        for az_package in az_packages:
            if az_package['name'] == vm_size:
                return az_package['numberOfCores'], az_package['memoryInMb']

if __name__ == "__main__":
    if sys.argv[1] == "az":
        vmList = []
        azureVms = cloudVirtualMachine()
        az_vms = azureVms.azureVmList()
        try:
            for vm in az_vms:
                vm_name = vm.id.split("/")[8]
                vm_core = azureVms.azCpuRam(vm.hardware_profile.vm_size)[0]
                vm_memory = azureVms.azCpuRam(vm.hardware_profile.vm_size)[1]
                vmList.append(vm_name + "_" + str(vm_core) + "_" + str(vm_memory))
            print json.dumps(vmList, sort_keys=True, separators=(',', ': '))
            sys.exit(1)
        except Exception as e:
            print str(e)
    else:
        instanceList = []
        awsInstances = cloudVirtualMachine()
        availableInstances = awsInstances.awsInstanceList()
        try:
            for instance in availableInstances:
                instanceProp = awsInstances.awsCpuRam(instance.instance_type)
                if instance.tags:
                    instance_name = instance.tags[0]['Value']
                else:
                    instance_name = "None"
                instanceList.append(str(instance.id) + "_" + str(instance_name) + "_" + str(instance.public_ip_address) + "_" + str(instance.state) + "_" + str(instance.launch_time) + "_"+instanceProp[0]+ "_" + instanceProp[1] + "_" + instanceProp[2])
            print json.dumps(instanceList, sort_keys=True, separators=(',', ': '))
            sys.exit(1)
        except Exception as e:
            print str(e)