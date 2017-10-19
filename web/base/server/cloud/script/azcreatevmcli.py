#!/usr/bin/env python
import os, sys, json
import commands

if __name__ == "__main__":
    vmCreateMsg = []
    try:
        disk_volume = sys.argv[1]
        azure_package = sys.argv[2]

        login_status, login_output = commands.getstatusoutput("az login --service-principal -u dee048ad-a200-41cf-9b4a-ce563aa06ecd -p Rv8LBsPbzPivFt5sw2fMW+77wIoD9hAlBfFS7AR4BYg= -t dfd034ad-c274-41ae-b40c-88199e6b7528")
        #print login_output
        create_group_status, create_group_output = commands.getstatusoutput("az group create --name HTResource --location CanadaEast")
        vnet_status, vnet_output = commands.getstatusoutput("az network vnet create --resource-group HTResource --name HTVNET --subnet-name HTSubNET")
        ip_status, ip_output = commands.getstatusoutput("az network public-ip create --resource-group HTResource --name HTPublicIP")
        nsg_status, nsg_output = commands.getstatusoutput("az network nsg create --resource-group HTResource --name HTNetworkSecurity")
        nic_status, nic_output = commands.getstatusoutput("az network nic create --resource-group HTResource --name HTNIC --vnet-name HTVNET --subnet HTSubNET --network-security-group HTNetworkSecurity --public-ip-address HTPublicIP")
        vm_create_status, vm_create_output = commands.getstatusoutput("sudo az vm create --resource-group HTResource --name HTVirtualMachine --nics HTNIC --image UbuntuLTS --data-disk-sizes-gb "+disk_volume+" --size "+azure_package+" --generate-ssh-keys --admin-username htbase --admin-password Htb@se123321")
        port_open_status, port_open_output = commands.getstatusoutput("az vm open-port --port 22 --resource-group HTResource --name HTVirtualMachine")
        logout_status, logout_output = commands.getstatusoutput("az logout")
        
        #data = json.loads(vm_create_output)
        #for key, value in data[0].iteritems():
            #vmCreateMsg.append(key+": "+value)
    except Exception as e:
        vmCreateMsg.append( str(e) )
    if(vmCreateMsg):
        print json.dumps(vmCreateMsg, sort_keys=True, separators=(',', ': '))
    else:
        print vm_create_output
    sys.exit(0)