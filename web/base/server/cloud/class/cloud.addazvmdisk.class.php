<?php
/**
 * Resource Select
 *
    htvcenter Enterprise developed by htvcenter Enterprise GmbH.

    All source code and content (c) Copyright 2014, htvcenter Enterprise GmbH unless specifically noted otherwise.

    This source code is released under the htvcenter Enterprise Server and Client License, unless otherwise agreed with htvcenter Enterprise GmbH.
    The latest version of this license can be found here: http://htvcenter-enterprise.com/license

    By using this software, you acknowledge having read this license and agree to be bound thereby.

                http://htvcenter-enterprise.com

    Copyright 2014, htvcenter Enterprise GmbH <info@htvcenter-enterprise.com>
 */

class addazvmdisk {
/**
* name of action buttons
* @access public
* @var string
*/
var $actions_name = 'addazvmdisk_action';
/**
* message param
* @access public
* @var string
*/
var $message_param = "addazvmdisk_msg";
/**
* id for tabs
* @access public
* @var string
*/
var $prefix_tab = 'addazvmdisk_tab';
/**
* identifier name
* @access public
* @var string
*/
var $identifier_name = 'addazvmdisk_identifier';
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
		$this->rootdir  = $this->htvcenter->get('webdir');
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
				$this->response->get_url($this->actions_name, 'select', $this->message_param, $response->msg)
			);
		}
		if(isset($response->error)) {
			$_REQUEST[$this->message_param] = $response->error;
		}
		$t = $this->response->html->template($this->tpldir.'/cloud-addazvmdisk.tpl.php');
		$t->add($this->lang['label'], 'label');
		$t->add($this->response->html->thisfile, "thisfile");
		$t->add($response->form);
		$t->add($this->htvcenter->get('baseurl'), 'baseurl');
		$t->add($this->lang['label'], 'form_add');
		$t->group_elements(array('param_' => 'form'));
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
			$memory 				= $form->get_request('memory');
			$operating_system 		= $form->get_request('operating_system');
			$vcpu 					= $form->get_request('vcpu');
			$disk_volume 			= $form->get_request('disk_volume');
			$disk_volume_type 		= $form->get_request('disk_volume_type');
			$azure_package			= $form->get_request('azure_package');
			$vm_monthly_price 		= $form->get_request('vm_monthly_price');
			
			$azure_package = 'Standard_A0';
			
			$command = shell_exec('sudo python '.$this->rootdir.'/server/cloud/script/azcreatevmcli.py ' . $disk_volume . ' ' . $azure_package);
			$azure_create_vm = json_decode($command, true);
			
			foreach($azure_create_vm as $k => $v){
				$data[] = $v;
			}
			
			if(empty($data)) {
				$response->msg = sprintf("Azure VM not created");
			} else {
				foreach($data as $d){
					$ab .= $d . "<br />";
				}
				$response->msg = sprintf($ab);
			}
			
			//$response->msg 			= "Clicked";
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
		$form = $response->get_form($this->actions_name, 'addazvmdisk');
		$submit = $form->get_elements('submit');
		$submit->handler = 'onclick="wait();"';
		$submit->value = 'Create virtual Machine';
		$form->add($submit, 'submit');

		$submit = $form->get_elements('cancel');
		$submit->handler = 'onclick="cancel();"';
		$form->add($submit, 'cancel');
		
		if(isset($_GET)){
			$params = unserialize(urldecode($_GET['params']));
			$vm_memory = $params['memory'];
			$vm_cpu = $params['cpu'];
			$vm_operating_system = $params['os'];
			$vm_monthly_price = $params['monthly_price'];
			$vm_base = trim($_GET['platform']);
		}
		
		$base = array(array( 'aws', 'aws' ), array('az', 'az') );
		$d['base']['label']												= $this->lang['base'];
		$d['base']['object']['type']									= 'htmlobject_select';
		$d['base']['object']['attrib']['index']							= array(0, 1);
		$d['base']['object']['attrib']['name']							= 'vm_base';
		$d['base']['object']['attrib']['id']							= 'vm_base';
		$d['base']['object']['attrib']['type']							= 'text';
		$d['base']['object']['attrib']['options']						= $base;
		$d['base']['object']['attrib']['selected']						= array($vm_base);
		
		$d['memory']['label']											= $this->lang['memory'];
		$d['memory']['object']['type']									= 'htmlobject_input';
		$d['memory']['object']['attrib']['name']						= 'memory';
		$d['memory']['object']['attrib']['id']							= 'memory';
		$d['memory']['object']['attrib']['type']						= 'text';
		$d['memory']['object']['attrib']['value']						= $vm_memory;
		
		$d['operating_system']['label']									= $this->lang['operating_system'];
		$d['operating_system']['object']['type']						= 'htmlobject_input';
		$d['operating_system']['object']['attrib']['name']				= 'operating_system';
		$d['operating_system']['object']['attrib']['id']				= 'operating_system';
		$d['operating_system']['object']['attrib']['type']				= 'text';
		$d['operating_system']['object']['attrib']['value']				= $vm_operating_system;
		
		$d['vcpu']['label']												= $this->lang['vcpu'];
		$d['vcpu']['object']['type']									= 'htmlobject_input';
		$d['vcpu']['object']['attrib']['name']							= 'vcpu';
		$d['vcpu']['object']['attrib']['id']							= 'vcpu';
		$d['vcpu']['object']['attrib']['type']							= 'text';
		$d['vcpu']['object']['attrib']['value']							= $vm_cpu;
		
		$disk_volume = array(array("", " -- "), array('32', '32 GB'), array('64', '64 GB'), array('128', '128 GB'), array('256', '256 GB'), array('512', '512 GB'), array('1024', '1024 GB (1 TB)'), array('2048', '2048 GB (2 TB)'), array('4096', '4096 GB (4 TB)') );
		
		$d['disk_volume']['label']										= $this->lang['disk_volume'];
		$d['disk_volume']['object']['type']								= 'htmlobject_select';
		$d['disk_volume']['object']['attrib']['index']					= array(0, 1);
		$d['disk_volume']['object']['attrib']['name']					= 'disk_volume';
		$d['disk_volume']['object']['attrib']['id']						= 'disk_volume';
		$d['disk_volume']['object']['attrib']['type']					= 'text';
		$d['disk_volume']['object']['attrib']['options']				= $disk_volume;
		
		$disk_volume_type = array(
			array("", " -- "),
			array("st", "Standard Managed Disks"),
			array("pr", "Premium Managed Disks")
		);
		
		$d['disk_volume_type']['label']									= $this->lang['disk_volume_type'];
		$d['disk_volume_type']['object']['type']						= 'htmlobject_select';
		$d['disk_volume_type']['object']['attrib']['index']				= array(0, 1);
		$d['disk_volume_type']['object']['attrib']['name']				= 'disk_volume_type';
		$d['disk_volume_type']['object']['attrib']['id']				= 'disk_volume_type';
		$d['disk_volume_type']['object']['attrib']['type']				= 'text';
		$d['disk_volume_type']['object']['attrib']['options']			= $disk_volume_type;
		
		$fileContent = file_get_contents($this->rootdir."/server/cloud/script/azurePackages.json");
		$jsonDecodedContent = json_decode($fileContent, true);
		$azure_package = array();
		$azure_package[] = array("", " -- ");
		$vm_memory_gib = str_replace("GiB", "", $vm_memory);
		foreach($jsonDecodedContent as $package) {
			if( ($package['numberOfCores'] == $vm_cpu) && ($vm_memory_gib == ceil($package['memoryInMb']) / 1024) ) {
				$azure_package[] = array($package['name'], $package['name']);
			}
		}
		$d['azure_package']['label']								= $this->lang['disk_volume_type'];
		$d['azure_package']['object']['type']						= 'htmlobject_select';
		$d['azure_package']['object']['attrib']['index']			= array(0, 1);
		$d['azure_package']['object']['attrib']['name']				= 'azure_package';
		$d['azure_package']['object']['attrib']['id']				= 'azure_package';
		$d['azure_package']['object']['attrib']['type']				= 'text';
		$d['azure_package']['object']['attrib']['options']			= $azure_package;

		$d['vm_monthly_price']['label']									= $this->lang['vm_monthly_price'];
		$d['vm_monthly_price']['object']['type']						= 'htmlobject_input';
		$d['vm_monthly_price']['object']['attrib']['name']				= 'vm_monthly_price';
		$d['vm_monthly_price']['object']['attrib']['id']				= 'vm_monthly_price';
		$d['vm_monthly_price']['object']['attrib']['type']				= 'text';
		$d['vm_monthly_price']['object']['attrib']['value']				= $vm_monthly_price;
		$d['vm_monthly_price']['object']['attrib']['maxlength']			= 50;
		
		$form->add($d);
		$response->form = $form;
		return $response;
	}
}
?>
