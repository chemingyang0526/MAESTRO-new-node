#!/usr/bin/env python

import boto3
import os, MySQLdb, json, sys
from phpserialize import serialize, unserialize

def createAWSInstance(ami_id, min_count, max_count, instance_type):
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
    ec2 = session.resource('ec2')
    
    instances = ec2.create_instances(
        ImageId=ami_id, 
        MinCount=min_count, 
        MaxCount=max_count, 
        InstanceType=instance_type,
        KeyName='HTBaseEC2',
        SecurityGroupIds=['sg-4d9c893d', 'sg-d227efad'],
    )
    if instances:
        return "Instance(s) created successfully"
    else:
        return "Instance(s) not created"

if __name__ == "__main__":
    parameters_size = len(sys.argv)
    if parameters_size != 5:
        sys.exit(1)
    instanceCreateMsg = []
    createInstance = createAWSInstance(sys.argv[1], int(sys.argv[2]), int(sys.argv[3]), sys.argv[4])
    instanceCreateMsg.append(createInstance)
    print json.dumps(instanceCreateMsg)
    sys.exit(0)
