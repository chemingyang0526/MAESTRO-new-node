<?php
/**
 * Storage Add
 *
    htvcenter Enterprise developed by htvcenter Enterprise GmbH.

    All source code and content (c) Copyright 2014, htvcenter Enterprise GmbH unless specifically noted otherwise.

    This source code is released under the htvcenter Enterprise Server and Client License, unless otherwise agreed with htvcenter Enterprise GmbH.
    The latest version of this license can be found here: http://htvcenter-enterprise.com/license

    By using this software, you acknowledge having read this license and agree to be bound thereby.

                http://htvcenter-enterprise.com

    Copyright 2014, htvcenter Enterprise GmbH <info@htvcenter-enterprise.com>
 */
class addawsvmdisk{
/**
* name of action buttons
* @access public
* @var string
*/
var $actions_name = 'addawsvmdisk_action';
/**
* message param
* @access public
* @var string
*/
var $message_param = "addawsvmdisk_msg";
/**
* id for tabs
* @access public
* @var string
*/
var $prefix_tab = 'addawsvmdisk_tab';
/**
* identifier name
* @access public
* @var string
*/
var $identifier_name = 'addawsvmdisk_identifier';
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
				$this->response->get_url($this->actions_name, 'awsinstance', $this->message_param, $response->msg)
			);
		}
		if(isset($response->error)) {
			$_REQUEST[$this->message_param] = $response->error;
		}
		$t = $this->response->html->template($this->tpldir.'/cloud-addawsvmdisk.tpl.php');
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
			$aws_package			= $form->get_request('aws_package');
			$vm_monthly_price 		= $form->get_request('vm_monthly_price');
			
			$create_instance = shell_exec('python '.$this->rootdir.'/server/cloud/script/awsinstancecheck.py '.$memory.' '.$operating_system.' '.$vcpu . ' ' . $aws_package);
			$create_instance_status = json_decode($create_instance, true);
			
			foreach($create_instance_status as $k => $v){
				$data[] = $v;
			}
			
			if(empty($data)) {
				$response->msg = sprintf("AWS Instance not created");
			} else {
				foreach($data as $d){
					$ab .= $d . "<br />";
				}
				$response->msg = sprintf($ab);
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
		$form = $response->get_form($this->actions_name, 'addawsvmdisk');
		$submit = $form->get_elements('submit');
		$submit->handler = 'onclick="wait();"';
		$submit->value = 'Create Virtual Machine';
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
			array("gp2", "Amazon EBS General Purpose SSD (gp2) volumes - $0.10 per GB-month"),
			array("io1", "Amazon EBS Provisioned IOPS SSD (io1) volumes - $0.125 per GB-month, $0.065 per provisioned IOPS-month"),
			array("st1", "Amazon EBS Throughput Optimized HDD (st1) volumes - $0.045 per GB-month"),
			array("sc1", "Amazon EBS Cold HDD (sc1) volumes - $0.025 per GB-month"),
		);
		
		$d['disk_volume_type']['label']									= $this->lang['disk_volume_type'];
		$d['disk_volume_type']['object']['type']						= 'htmlobject_select';
		$d['disk_volume_type']['object']['attrib']['index']				= array(0, 1);
		$d['disk_volume_type']['object']['attrib']['name']				= 'disk_volume_type';
		$d['disk_volume_type']['object']['attrib']['id']				= 'disk_volume_type';
		$d['disk_volume_type']['object']['attrib']['type']				= 'text';
		$d['disk_volume_type']['object']['attrib']['options']			= $disk_volume_type;
		
		$ram = str_replace(" GiB", "", $vm_memory);
		
		$aws_package = array(array("", " -- "));
		$$aws_package_temp = $this->instancePackage($vm_cpu, $ram);
		foreach($$aws_package_temp as $val){
			$aws_package[] = array($val, $val);
		}
		
		$d['aws_package']['label']										= $this->lang['aws_package'];
		$d['aws_package']['object']['type']								= 'htmlobject_select';
		$d['aws_package']['object']['attrib']['index']					= array(0, 1);
		$d['aws_package']['object']['attrib']['name']					= 'aws_package';
		$d['aws_package']['object']['attrib']['id']						= 'aws_package';
		$d['aws_package']['object']['attrib']['type']					= 'text';
		$d['aws_package']['object']['attrib']['options']				= $aws_package;

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
	
	function instancePackage($vcpu, $ram){
		$packageString = array(
			"t1.micro-1-0.613", "t2.nano-1-0.5", "t2.micro-1-1", "t2.small-1-2", "t2.medium-2-4", "t2.large-2-8", "t2.xlarge-4-16", "t2.2xlarge-8-32",
			"m4.large-2-8", "m4.xlarge-4-16", "m4.2xlarge-8-32", "m4.4xlarge-16-64", "m4.10xlarge-40-160", "m4.16xlarge-64-256", "m3.medium-1-3.75", "m3.large-2-7.5", "m3.xlarge-4-15", "m3.2xlarge-8-30", "m1.small-1-1.7", "m1.medium-1-3.7", "m1.large-2-7.5", "m1.xlarge-4-15",
			"c4.large-2-3.75", "c4.xlarge-4-7.5", "c4.2xlarge-8-15", "c4.4xlarge-16-30", "c4.8xlarge-36-60", "c3.large-2-3.75", "c3.xlarge-4-7.5", "c3.2xlarge-8-15", "c3.4xlarge-16-30", "c3.8xlarge-32-60", "c1.medium-2-1.7", "c1.xlarge-8-7", "cc2.8xlarge-32-60.5", "cc1.4xlarge-16-23",
			"f1.2xlarge-8-122", "f1.16xlarge-64-976",
			"g3.4xlarge-16-122", "g3.8xlarge-32-244", "g3.16xlarge-64-488", "g2.2xlarge-8-15", "g2.8xlarge-32-60", "cg1.4xlarge-16-22",
			"p2.xlarge-4-61", "p2.8xlarge-32-488", "p2.16xlarge-64-732",
			"r4.large-2-15.25", "r4.xlarge-4-30.5", "r4.2xlarge-8-61", "r4.4xlarge-16-122", "r4.8xlarge-32-244", "r4.16xlarge-64-488", "r3.large-2-15", "r3.xlarge-4-30.5", "r3.2xlarge-8-61", "r3.4xlarge-16-122", "r3.8xlarge-32-244",
			"x1.16xlarge-64-976", "x1e.32xlarge-128-3904", "x1.32xlarge-128-1952",
			"m2.xlarge-2-17.1", "m2.2xlarge-4-34.2", "m2.4xlarge-8-68.4",
			"cr1.8xlarge-32-244", "d2.xlarge-4-30.5", "d2.2xlarge-8-61", "d2.4xlarge-16-122", "d2.8xlarge-36-244",
			"i2.xlarge-4-30.5", "i2.2xlarge-8-61", "i2.4xlarge-16-122", "i2.8xlarge-32-244", "i3.large-2-15.25", "i3.xlarge-4-30.5", "i3.2xlarge-8-61", "i3.4xlarge-16-122", "i3.8xlarge-32-244", "i3.16xlarge-64-488", "hi1.4xlarge-16-60.5", "hs1.8xlarge-16-117"
		);
		$packagelist = array();
		foreach($packageString as $val) {
			$temp = explode("-", $val);
			if($temp[1] == $vcpu && $temp[2] == $ram) {
				$packagelist [] = $temp[0];
			}
		}
		return $packagelist;
	}
}
?>
