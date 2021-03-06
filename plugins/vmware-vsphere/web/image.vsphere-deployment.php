<?php
/*
    HyperTask Enterprise developed by HyperTask Enterprise GmbH.

    All source code and content (c) Copyright 2014, HyperTask Enterprise GmbH unless specifically noted otherwise.

    This source code is released under the HyperTask Enterprise Server and Client License, unless otherwise agreed with HyperTask Enterprise GmbH.
    The latest version of this license can be found here: http://htvcenter-enterprise.com/license

    By using this software, you acknowledge having read this license and agree to be bound thereby.

                http://htvcenter-enterprise.com

    Copyright 2014, HyperTask Enterprise GmbH <info@htvcenter-enterprise.com>
*/


// error_reporting(E_ALL);

$RootDir = $_SERVER["DOCUMENT_ROOT"].'/htvcenter/base/';
require_once "$RootDir/include/htvcenter-database-functions.php";
require_once "$RootDir/include/user.inc.php";
require_once "$RootDir/include/htvcenter-server-config.php";
require_once "$RootDir/class/storage.class.php";
require_once "$RootDir/class/resource.class.php";
require_once "$RootDir/class/event.class.php";
require_once "$RootDir/class/htvcenter_server.class.php";
global $htvcenter_SERVER_BASE_DIR;

// global event for logging
$event = new event();
global $event;

function vsphere_deployment_wait_for_identfile($sfile) {
	$refresh_delay=1;
	$refresh_loop_max=20;
	$refresh_loop=0;
	while (!file_exists($sfile)) {
		sleep($refresh_delay);
		$refresh_loop++;
		flush();
		if ($refresh_loop > $refresh_loop_max)  {
			return false;
		}
	}
	return true;
}


function get_vsphere_deployment_image_rootdevice_identifier($vsphere_id) {
	global $htvcenter_SERVER_BASE_DIR;
	global $htvcenter_ADMIN;
	global $event;

	// place for the storage stat files
	$StorageDir = $_SERVER["DOCUMENT_ROOT"].'/htvcenter/base/plugins/vsphere/vmware-vsphere-stat/';
	$rootdevice_identifier_array = array();
	$storage = new storage();
	$storage->get_instance_by_id($vsphere_id);
	// get deployment type
	$deployment = new deployment();
	$deployment->get_instance_by_id($storage->type);
	// get storage resource
	$storage_resource = new resource();
	$storage_resource->get_instance_by_id($storage->resource_id);
	$storage_resource_id = $storage_resource->id;
	$ident_file = $StorageDir."/".$storage_resource_id.".vsphere-deployment.ident";
	if (file_exists($ident_file)) {
		unlink($ident_file);
	}
	// send command
	$resource_command=$htvcenter_SERVER_BASE_DIR."/htvcenter/plugins/vsphere/bin/htvcenter-vsphere post_identifier -i ".$storage_resource->ip." --htvcenter-cmd-mode background";
	$storage_resource->send_command($storage_resource->ip, $resource_command);
	if (!vsphere_deployment_wait_for_identfile($ident_file)) {
		$event->log("get_image_rootdevice_identifier", $_SERVER['REQUEST_TIME'], 2, "image.vsphere-deployment", "Timeout while requesting image identifier from storage id $storage->id", "", "", 0, 0, 0);
		return;
	}
	$fcontent = file($ident_file);
	foreach($fcontent as $lun_info) {
		$ident_params = explode(":", $lun_info);
		$timage_name = trim($ident_params[0]);
		$troot_device = trim($ident_params[1]);
		$rootdevice_identifier_array[] = array("value" => "$troot_device", "label" => "$timage_name");
	}
	return $rootdevice_identifier_array;
}


function get_vsphere_deployment_image_default_rootfs() {
	return "local";
}

function get_vsphere_deployment_rootfs_transfer_methods() {
	return false;
}

function get_vsphere_deployment_rootfs_set_password_method() {
	return true;
}

function get_vsphere_deployment_is_network_deployment() {
	return false;
}

function get_vsphere_deployment_local_deployment_enabled() {
	return true;
}



?>

