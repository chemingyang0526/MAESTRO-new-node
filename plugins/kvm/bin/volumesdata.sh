#!/bin/bash

action=$1
vmname=$2
num=$3
sizevol=$4

prefix='KVM_VM_DISK_SIZE_'

if [ "$action" == 'getlist' ]; then
sudo rm -rf /var/www/html/${vmname}_volume_data
	
	# for i in `seq 2 10`;
     #  do
     #          source /var/lib/kvm/htvcenter/$vmname/disk$i
     #          size=$(eval echo "\$$prefix$i")
     # 
     #          	if [ "$size" != "" ]; then
   # 				echo "<tr class=\"content\" num=\"$i\"><td> $size </td><td><a class='voldel'><i class=\"fa fa-close\"></i> Remove volume</a></td></tr>" >> /var/www/html/${vmname}_volume_data;
	#			else
	#				echo "<tr class=\"content\" num=\"$i\"><td> no volume</td><td><a class='voladd'><i class=\"fa fa-plus\"></i> Add volume</a></td></tr>" >> /var/www/html/${vmname}_volume_data;
	#			fi             
     #  done    

     i=2
     source /var/lib/kvm/htvcenter/$vmname/disk$i
              size=$KVM_VM_DISK_SIZE_2
      
        if [ "$size" != "" ]; then
   				echo "<tr class=\"content\" num=\"$i\"><td> $size </td><td><a class='voldel'><i class=\"fa fa-close\"></i> Remove volume</a></td></tr>" >> /var/www/html/${vmname}_volume_data;
				else
					echo "<tr class=\"content\" num=\"$i\"><td> no volume</td><td><a class='voladd'><i class=\"fa fa-plus\"></i> Add volume</a></td></tr>" >> /var/www/html/${vmname}_volume_data;
				fi  

     i=3
     source /var/lib/kvm/htvcenter/$vmname/disk$i
              size=$KVM_VM_DISK_SIZE_3
      
               	if [ "$size" != "" ]; then
   				echo "<tr class=\"content\" num=\"$i\"><td> $size </td><td><a class='voldel'><i class=\"fa fa-close\"></i> Remove volume</a></td></tr>" >> /var/www/html/${vmname}_volume_data;
				else
					echo "<tr class=\"content\" num=\"$i\"><td> no volume</td><td><a class='voladd'><i class=\"fa fa-plus\"></i> Add volume</a></td></tr>" >> /var/www/html/${vmname}_volume_data;
				fi  
     i=4
     source /var/lib/kvm/htvcenter/$vmname/disk$i
              size=$KVM_VM_DISK_SIZE_4
      
               	if [ "$size" != "" ]; then
   				echo "<tr class=\"content\" num=\"$i\"><td> $size </td><td><a class='voldel'><i class=\"fa fa-close\"></i> Remove volume</a></td></tr>" >> /var/www/html/${vmname}_volume_data;
				else
					echo "<tr class=\"content\" num=\"$i\"><td> no volume</td><td><a class='voladd'><i class=\"fa fa-plus\"></i> Add volume</a></td></tr>" >> /var/www/html/${vmname}_volume_data;
				fi  

     i=5
     source /var/lib/kvm/htvcenter/$vmname/disk$i
              size=$KVM_VM_DISK_SIZE_5
      
               	if [ "$size" != "" ]; then
   				echo "<tr class=\"content\" num=\"$i\"><td> $size </td><td><a class='voldel'><i class=\"fa fa-close\"></i> Remove volume</a></td></tr>" >> /var/www/html/${vmname}_volume_data;
				else
					echo "<tr class=\"content\" num=\"$i\"><td> no volume</td><td><a class='voladd'><i class=\"fa fa-plus\"></i> Add volume</a></td></tr>" >> /var/www/html/${vmname}_volume_data;
				fi  

     i=6
     source /var/lib/kvm/htvcenter/$vmname/disk$i
              size=$KVM_VM_DISK_SIZE_6
      
               	if [ "$size" != "" ]; then
   				echo "<tr class=\"content\" num=\"$i\"><td> $size </td><td><a class='voldel'><i class=\"fa fa-close\"></i> Remove volume</a></td></tr>" >> /var/www/html/${vmname}_volume_data;
				else
					echo "<tr class=\"content\" num=\"$i\"><td> no volume</td><td><a class='voladd'><i class=\"fa fa-plus\"></i> Add volume</a></td></tr>" >> /var/www/html/${vmname}_volume_data;
				fi  

     i=7
     source /var/lib/kvm/htvcenter/$vmname/disk$i
              size=$KVM_VM_DISK_SIZE_7
      
               	if [ "$size" != "" ]; then
   				echo "<tr class=\"content\" num=\"$i\"><td> $size </td><td><a class='voldel'><i class=\"fa fa-close\"></i> Remove volume</a></td></tr>" >> /var/www/html/${vmname}_volume_data;
				else
					echo "<tr class=\"content\" num=\"$i\"><td> no volume</td><td><a class='voladd'><i class=\"fa fa-plus\"></i> Add volume</a></td></tr>" >> /var/www/html/${vmname}_volume_data;
				fi  

     i=8
     source /var/lib/kvm/htvcenter/$vmname/disk$i
              size=$KVM_VM_DISK_SIZE_8
      
               	if [ "$size" != "" ]; then
   				echo "<tr class=\"content\" num=\"$i\"><td> $size </td><td><a class='voldel'><i class=\"fa fa-close\"></i> Remove volume</a></td></tr>" >> /var/www/html/${vmname}_volume_data;
				else
					echo "<tr class=\"content\" num=\"$i\"><td> no volume</td><td><a class='voladd'><i class=\"fa fa-plus\"></i> Add volume</a></td></tr>" >> /var/www/html/${vmname}_volume_data;
				fi  

     i=9
     source /var/lib/kvm/htvcenter/$vmname/disk$i
              size=$KVM_VM_DISK_SIZE_9
      
               	if [ "$size" != "" ]; then
   				echo "<tr class=\"content\" num=\"$i\"><td> $size </td><td><a class='voldel'><i class=\"fa fa-close\"></i> Remove volume</a></td></tr>" >> /var/www/html/${vmname}_volume_data;
				else
					echo "<tr class=\"content\" num=\"$i\"><td> no volume</td><td><a class='voladd'><i class=\"fa fa-plus\"></i> Add volume</a></td></tr>" >> /var/www/html/${vmname}_volume_data;
				fi  
     i=10
     source /var/lib/kvm/htvcenter/$vmname/disk$i
              size=$KVM_VM_DISK_SIZE_10
      
               	if [ "$size" != "" ]; then
   				echo "<tr class=\"content\" num=\"$i\"><td> $size </td><td><a class='voldel'><i class=\"fa fa-close\"></i> Remove volume</a></td></tr>" >> /var/www/html/${vmname}_volume_data;
				else
					echo "<tr class=\"content\" num=\"$i\"><td> no volume</td><td><a class='voladd'><i class=\"fa fa-plus\"></i> Add volume</a></td></tr>" >> /var/www/html/${vmname}_volume_data;
				fi        
fi

if [ "$action" == 'remove' ]; then
	numeo=$(($num - 2)) 
	rm -rf /var/lib/kvm/storage1/${vmname}vol$numeo
	rm -rf /var/lib/kvm/htvcenter/$vmname/disk$num
	rm -rf /usr/share/htvcenter/storage/${vmname}vol$numeo
	echo "KVM_VM_DISK_$num=\"\"" >> /var/lib/kvm/htvcenter/$vmname/disk$num
	echo "KVM_VM_DISK_SIZE_$num=\"\"" >> /var/lib/kvm/htvcenter/$vmname/disk$num
fi

if [ "$action" == 'add' ]; then
	numeo=$(($num - 2))
	volname=${vmname}vol$numeo
	rm -rf /var/lib/kvm/htvcenter/$vmname/disk$num
#	echo "KVM_VM_DISK_$num=\"$volname\"" >> /var/lib/kvm/htvcenter/$vmname/disk$num
	echo "KVM_VM_DISK_$num=\"/usr/share/htvcenter/storage/$volname\"" >> /var/lib/kvm/htvcenter/$vmname/disk$num
	echo "KVM_VM_DISK_SIZE_$num=\"$sizevol\"" >> /var/lib/kvm/htvcenter/$vmname/disk$num
fi
