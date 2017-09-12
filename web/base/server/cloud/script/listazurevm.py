#!/usr/bin/env python

import os, sys, json
import configuration

if __name__ == "__main__":
    vmList = []
    count = 0
    for vm in configuration.compute_client.virtual_machines.list_all():
        vmID = vm.id
        resourceGroup = vmID.split("/")[4]
        v_m = configuration.compute_client.virtual_machines.get(resourceGroup, vm.name, expand = 'instanceview')
        
        nic_reference = vm.network_profile.network_interfaces[0]
        nic_reference = nic_reference.id.split('/')
        nic_group = nic_reference[4]
        nic_name = nic_reference[8]
        
        net_interface = configuration.network_client.network_interfaces.get(nic_group, nic_name)
        ip_reference = net_interface.ip_configurations[0].public_ip_address
        if ip_reference:
            ip_reference = ip_reference.id.split('/')
            ip_group = ip_reference[4]
            ip_name = ip_reference[8]

            public_ip = configuration.network_client.public_ip_addresses.get(ip_group, ip_name)
            public_ip = public_ip.ip_address
            #print public_ip
        else:
            public_ip = "Not assigned"
        vmList.append(str(vm.name) + '_*_' + str(vm.location) + '_*_' + str(vm.storage_profile.image_reference.sku) + '_*_' + str(vm.vm_id) + '_*_' + str(vm.type) + '_*_' + str(v_m.instance_view.statuses[1].display_status) + '_*_' + str(resourceGroup) + '_*_' + str(public_ip))
    print json.dumps(vmList, sort_keys=True, separators=(',', ': '))
