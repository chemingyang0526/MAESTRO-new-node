<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);


/**
 * Datacenter Dashboard
 *
    htvcenter Enterprise developed by HTBase Corp.

    All source code and content (c) Copyright 2015, HTBase Corp unless specifically noted otherwise.

    This source code is released under the htvcenter Enterprise Server and Client License, unless otherwise agreed with HTBase Corp.

    By using this software, you acknowledge having read this license and agree to be bound thereby.

                http://www.htbase.com

    Copyright 2015, HTBase Corp <contact@htbase.com>
 */

class addcompose {
/**
* name of action buttons
* @access public
* @var string
*/
var $actions_name = 'compose_action';
/**
* message param
* @access public
* @var string
*/
var $message_param = "compose_msg";

/**
* id for tabs
* @access public
* @var string
*/
var $prefix_tab = 'compose_tab';
/**
* path to templates
* @access public
* @var string
*/
var $tpldir;
/**
* translation
* @access public
* @var array
*/
var $lang = array();

//--------------------------------------------
	/**
 	* Constructor
	 *
	 * @access public
	 * @param htvcenter $htvcenter
	 * @param htmlobject_response $response
	 */
//--------------------------------------------
function __construct($htvcenter, $response) {
	$this->response   = $response;
	$this->file       = $htvcenter->file();
	$this->htvcenter    = $htvcenter;
	$this->rootdir			= $this->htvcenter->get('webdir');
	require_once $this->rootdir."/include/htvcenter-database-functions.php";
	$this->db 				= htvcenter_get_db_connection();
}

//--------------------------------------------
	/**
	 * Action
	 *
	 * @access public
	 * @return htmlobject_template
	 */
//--------------------------------------------
function action() {
	$response = $this->add();
	if(isset($response->msg)) {
		$this->response->redirect(
			$this->response->get_url('', '', $this->message_param, $response->msg)
		);
	}
	if(isset($response->error)) {
		$_REQUEST[$this->message_param] = $response->error;
	}
	$t = $this->response->html->template($this->tpldir.'/compose-add.tpl.php');
	$t->add($this->lang['label'], 'label');
	$t->add($this->response->html->thisfile, "thisfile");
	$t->add($response->form);
	$t->add($this->htvcenter->get('baseurl'), 'baseurl');
	$t->add($this->lang['label'], 'form_add');
	$t->add($response->compose_name, 'compose_name');
	$t->add($response->compose_id, 'compose_id');
	$t->group_elements(array('param_' => 'form'));
	$t->group_elements(array('faram_' => 'form_f'));
	return $t;
}


//--------------------------------------------
/**
 * Add
 *
 * @access public
 * @return htmlobject_response
 */
//--------------------------------------------
function add() {
	$response = $this->get_response();
	$form     = $response->form;
	if(!$form->get_errors() && $this->response->submit()) {
		$composeID = $_GET['compose_id'];
		$composeStatus = $form->get_request('compose_status');
		
		$applianceIDs = "";
		$mem_total = 0;
		$cpuTotal = 0;
		$count = 1;
		foreach($_GET['appNames'] as $appName) {
			if($appName){
				if(count($_GET['appNames']) == $count ){
					$applianceIDs = $applianceIDs.$appName;
				} else {
					$applianceIDs = $applianceIDs.$appName.",";
				}
			}
			
			$data = $this->db->GetAll("SELECT * FROM maestro_compose WHERE id = ".$composeID);
			$compose_type = $data[0]['compose_type'];
			$comp_type = explode(",", $compose_type);
			
			if($comp_type[0] == "cloud"){
				if(trim($comp_type[1]) == "aws" || trim($comp_type[1]) == "AWS"){
					$ec2_info_dump = shell_exec('python '.$this->rootdir.'/server/aa_server/js/cloudvms.py aws');
					$ec2_info = json_decode($ec2_info_dump, true);
					foreach($ec2_info as $k => $v) {
						$temp = explode("_", $v);
						if($temp[0] == $appName) {
							$mem_total = $mem_total + (int) $temp[7];
							$cpuTotal = $cpuTotal + (int) $temp[6];
						}
					}
				} else if(trim($comp_type[1]) == "azure" || trim($comp_type[1]) == "AZURE") {
					$ec2_info_dump = shell_exec('python '.$this->rootdir.'/server/aa_server/js/cloudvms.py az');
					$ec2_info = json_decode($ec2_info_dump, true);
					foreach($ec2_info as $k => $v) {
						$temp = explode("_", $v);
						if($temp[0] == $appName) {
							$memInGB = ($temp[2] / 1024);
							$memInGB = number_format((float) $memInGB, 2, '.', '');
							$mem_total = $mem_total + $memInGB;
							$cpuTotal = $cpuTotal + (int) $temp[1];
						}
					}
				}
			} else {
				$mem_total = $mem_total + $this->getResourceMemory($appName);
				$mem_total = ($mem_total / 1024);
				$mem_total = number_format((float) $mem_total, 2, '.', '');
				$cpuTotal = $cpuTotal + $this->getResourceCPU($appName);
			}
			$count++;
		}
		
		$dbUpdate = $this->db->Execute("UPDATE `maestro_compose` SET compose_memory = '".$mem_total."', compose_cpu = '".$cpuTotal."', compose_appliances = '".$applianceIDs."', compose_status = ".$composeStatus.", edit_date = '".date("Y-m-d")."' WHERE id=".$composeID);
		if($dbUpdate) {
			$response->msg = sprintf("Compose Server updated successfully");
		} else {
			$response->msg = sprintf("Compose server did not updated");
		}
	}
	return $response;
}

//--------------------------------------------
/**
 * Get Response
 *
 * @access public
 * @return htmlobject_response
 */
//--------------------------------------------
function get_response() {
	$response = $this->response;
	$form = $response->get_form($this->actions_name, 'editcompose');
	$submit = $form->get_elements('submit');
	$submit->handler = 'onclick="wait();"';
	$submit->value = 'Edit Compose';
	$form->add($submit, 'submit');

	$submit = $form->get_elements('cancel');
	$submit->handler = 'onclick="cancel();"';
	$form->add($submit, 'cancel');
	
	$composeID = $_GET['composeID'];
	$composeData = $this->db->GetAll("SELECT * FROM `maestro_compose` WHERE id=".$composeID);
	$composeName = $composeData[0]['compose_name'];
	$composeAppliances = explode(",", $composeData[0]['compose_appliances']);
	$composeStatus = $composeData[0]['compose_status'];
	
	$status_options = array(array(1, 'active'), array(0, 'inactive'));
	$d['compose_status']['label']                        = 'Status';
	$d['compose_status']['object']['type']               = 'htmlobject_select';
	$d['compose_status']['object']['attrib']['index']    = array(0, 1);
	$d['compose_status']['object']['attrib']['name']     = 'compose_status';
	$d['compose_status']['object']['attrib']['options']  = $status_options;
	$d['compose_status']['object']['attrib']['selected'] = array($composeStatus);
	
	$i = 0;
	foreach($composeAppliances as $composeAppliance){
		if($composeAppliance) {
			if($this->getApplianceName($composeAppliance)){
				$d['param_f'.$i]['label']                       = $this->getApplianceName($composeAppliance);
			} else {
				$d['param_f'.$i]['label']                       = $composeAppliance;
			}
			$d['param_f'.$i]['object']['type']              = 'htmlobject_input';
			$d['param_f'.$i]['object']['attrib']['type']    = 'checkbox';
			$d['param_f'.$i]['object']['attrib']['name']    = 'appNames[]';
			$d['param_f'.$i]['object']['attrib']['value']   = trim($composeAppliance);
			$d['param_f'.$i]['object']['attrib']['checked'] = true;
			$i++;
		}
	}
	
	
	$compose_resource_array = array();
	$dbSql = $this->db->GetAll("SELECT * FROM `maestro_compose`");
	for ($i = 0; $i < count($dbSql); $i++) {
		$appTemp = explode(",", $dbSql[$i]['compose_appliances']);
		foreach($appTemp as $app){
			if($app){
				$compose_resource_array[] = $app;
			}
		}
	}
	
	$compose_type = $composeData[0]['compose_type'];
	$composeType = explode(",", $compose_type);
	if($composeType[0] == "cloud"){
		if(trim($composeType[1]) == "aws" || trim($composeType[1]) == "AWS"){
			$ec2_info_dump = shell_exec('python '.$this->rootdir.'/server/aa_server/js/cloudvms.py aws');
			$ec2_info = json_decode($ec2_info_dump, true);
			foreach($ec2_info as $k => $v) {
				$temp = explode("_", $v);
				if( !in_array($temp[0], $compose_resource_array)) {
					$i++;
					$e['faram_f'.$i]['label']                       = $temp[0];
					$e['faram_f'.$i]['object']['type']              = 'htmlobject_input';
					$e['faram_f'.$i]['object']['attrib']['type']    = 'checkbox';
					$e['faram_f'.$i]['object']['attrib']['name']    = 'appNames[]';
					$e['faram_f'.$i]['object']['attrib']['value']   = $temp[0];
					$e['faram_f'.$i]['object']['attrib']['checked'] = false;
				}
			}
		} else if(trim($composeType[1]) == "azure" || trim($composeType[1]) == "AZURE"){
			$ec2_info_dump = shell_exec('python '.$this->rootdir.'/server/aa_server/js/cloudvms.py az');
			$ec2_info = json_decode($ec2_info_dump, true);
			foreach($ec2_info as $k => $v) {
				$temp = explode("_", $v);
				if( !in_array($temp[0], $compose_resource_array)) {
					$i++;
					$e['faram_f'.$i]['label']                       = $temp[0];
					$e['faram_f'.$i]['object']['type']              = 'htmlobject_input';
					$e['faram_f'.$i]['object']['attrib']['type']    = 'checkbox';
					$e['faram_f'.$i]['object']['attrib']['name']    = 'appNames[]';
					$e['faram_f'.$i]['object']['attrib']['value']   = $temp[0];
					$e['faram_f'.$i]['object']['attrib']['checked'] = false;
				}
			}
		}
	} else {
		$resourceData = $this->db->GetAll("SELECT resource_id, resource_vhostid, resource_hostname FROM `resource_info`");
		foreach($resourceData as $resource) {
			if( ($resource['resource_id'] == $resource['resource_vhostid']) && ($resource['resource_id'] != 0) && !in_array($resource['resource_id'], $compose_resource_array)) {
				$i++;
				$e['faram_f'.$i]['label']                       = $resource['resource_hostname'];
				$e['faram_f'.$i]['object']['type']              = 'htmlobject_input';
				$e['faram_f'.$i]['object']['attrib']['type']    = 'checkbox';
				$e['faram_f'.$i]['object']['attrib']['name']    = 'appNames[]';
				$e['faram_f'.$i]['object']['attrib']['value']   = trim($resource['resource_id']);
				$e['faram_f'.$i]['object']['attrib']['checked'] = false;
			}
		}
	}
		
	$response->compose_name = $composeData[0]['compose_name'];
	$response->compose_id = $composeData[0]['id'];
	
	$form->add($d);
	$form->add($e);
	
	$response->form = $form;
	return $response;
}

function getApplianceName($id){
	$appSql = $this->db->GetAll("SELECT resource_hostname FROM resource_info WHERE resource_id=".$id);
	return $appSql[0]['resource_hostname'];
}

function getResourceMemory($id){
	$appSql = $this->db->GetAll("SELECT resource_memtotal FROM resource_info WHERE resource_id=".$id);
	return $appSql[0]['resource_memtotal'];
}

function getResourceCPU($id){
	$appSql = $this->db->GetAll("SELECT resource_cpunumber FROM resource_info WHERE resource_id=".$id);
	return $appSql[0]['resource_cpunumber'];
}

}
?>