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

class addawsinstance {
/**
* name of action buttons
* @access public
* @var string
*/
var $actions_name = 'addawsinstance';
/**
* message param
* @access public
* @var string
*/
var $message_param = "aws_config_msg";
/**
* id for tabs
* @access public
* @var string
*/
var $prefix_tab = 'aws_config_tab';
/**
* identifier name
* @access public
* @var string
*/
var $identifier_name = 'aws_config_identifier';
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
		$t = $this->response->html->template($this->tpldir.'/cloud-add-aws-instance.tpl.php');
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
			$aws_ami_id					= trim($form->get_request('aws_ami_id'));
			$aws_instance_min			= trim($form->get_request('aws_instance_min'));
			$aws_instance_max			= trim($form->get_request('aws_instance_max'));
			$aws_instance_type			= trim($form->get_request('aws_instance_type'));
			
			$command = shell_exec('python '.$this->rootdir.'/server/storage/script/createawsinstance.py '.$aws_ami_id. ' '.$aws_instance_min.' '.$aws_instance_max .' '.$aws_instance_type);
			$aws_create_instance = json_decode($command, true);
			
			foreach($aws_create_instance as $k => $v){
				$data[] = $v;
			}
			
			if(empty($data)) {
				$response->msg = sprintf("Instance(s) not created");
			} else {
				foreach($data as $d){
					$response->msg = sprintf("Instance(s) created successfully");
				}
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
		$form = $response->get_form($this->actions_name, 'addawsinstance');
		$submit = $form->get_elements('submit');
		$submit->handler = 'onclick="wait();"';
		$submit->value = 'Add AWS Instances (EC2)';
		$form->add($submit, 'submit');

		$submit = $form->get_elements('cancel');
		$submit->handler = 'onclick="cancel();"';
		$form->add($submit, 'cancel');
		
		
		/*array('Maestro Controller', 'ami-c27a28b9'), Controller AMI
		array('Client on Maestro 64 GB SSD (GP2)', 'ami-10ae916b'),
		array('Ubuntu Server 16.04 LTS (HMV)', 'ami-cd0f5cb6'),
		array('Ubuntu Server 14.04 LTS (HVM), SSD Volume Type', 'ami-841f46ff'),
		array('Amazon Linux AMI 2017.03.1 (HVM), SSD Volume Type', 'ami-4fffc834'),
		array('SUSE Linux Enterprise Server 12 SP2 (HVM), SSD Volume Type','ami-8fac8399'),
		array('Red Hat Enterprise Linux 7.4 (HVM), SSD Volume Type', 'ami-c998b6b2'),
		array('Microsoft Windows Server 2016 Base','ami-27a58d5c'),*/
		
		$aws_ami_id_list = array(
			array('MaestroClient Ubuntu 14', 'ami-c13137ba'),
			array('MaestroClient Ubuntu 16', 'ami-ac3e38d7'),
			array('MaestroClient CentOS 6', 'ami-023e3879'),
			array('MaestroClient CentOS 7', 'ami-203f395b'),
			array('Ubuntu Server 16.04 LTS (HMV)', 'ami-cd0f5cb6'),
			array('Ubuntu Server 14.04 LTS (HVM), SSD Volume Type', 'ami-841f46ff'),
		); 
		$d['aws_ami_id']['label']                            = $this->lang['aws_ami_id'];
		$d['aws_ami_id']['required']                         = true;
		$d['aws_ami_id']['object']['type']                   = 'htmlobject_select';
		$d['aws_ami_id']['object']['attrib']['index']   	   = array(1, 0);
		$d['aws_ami_id']['object']['attrib']['name']         = 'aws_ami_id';
		$d['aws_ami_id']['object']['attrib']['id']           = 'aws_ami_id';
		$d['aws_ami_id']['object']['attrib']['type']         = 'text';
		$d['aws_ami_id']['object']['attrib']['value']        = $aws_ami_id;
		$d['aws_ami_id']['object']['attrib']['options']      = $aws_ami_id_list;
		
		$aws_instance_min_options = array( array(1, 1), array(2, 2), array(3, 3), array(4, 4), array(5, 5), array(6, 6), array(7, 7), array(8, 8), array(9, 9) ); 
		$d['aws_instance_min']['label']                            = $this->lang['aws_instance_min'];
		$d['aws_instance_min']['required']                         = true;
		$d['aws_instance_min']['object']['type']                   = 'htmlobject_select';
		$d['aws_instance_min']['object']['attrib']['index']   	   = array(1, 0);
		$d['aws_instance_min']['object']['attrib']['name']         = 'aws_instance_min';
		$d['aws_instance_min']['object']['attrib']['id']           = 'aws_instance_min';
		$d['aws_instance_min']['object']['attrib']['type']         = 'text';
		$d['aws_instance_min']['object']['attrib']['value']        = $aws_instance_min;
		$d['aws_instance_min']['object']['attrib']['options']      = $aws_instance_min_options;
		
		$aws_instance_max_options = array( array(5, 5), array(1, 1), array(2, 2), array(3, 3), array(4, 4), array(6, 6), array(7, 7), array(8, 8), array(9, 9) ); 
		$d['aws_instance_max']['label']                            = $this->lang['aws_instance_max'];
		$d['aws_instance_max']['required']                         = true;
		$d['aws_instance_max']['object']['type']                   = 'htmlobject_select';
		$d['aws_instance_max']['object']['attrib']['index']   	   = array(1, 0);
		$d['aws_instance_max']['object']['attrib']['name']         = 'aws_instance_max';
		$d['aws_instance_max']['object']['attrib']['id']           = 'aws_instance_max';
		$d['aws_instance_max']['object']['attrib']['type']         = 'text';
		$d['aws_instance_max']['object']['attrib']['value']        = $aws_instance_max;
		$d['aws_instance_max']['object']['attrib']['options']      = $aws_instance_max_options;
		
		$available_instance_types = $this->instancePackage(); //array( array('t2.nano', 't2.nano'), array('t2.micro', 't2.micro'), array('t2.small', 't2.small'), array('t2.medium', 't2.medium'), array('t2.large', 't2.large'), array('t2.xlarge', 't2.xlarge'), array('t2.2xlarge', 't2.2xlarge'), array('m4.large', 'm4.large'), array('m4.xlarge', 'm4.xlarge') ); 
		
		$d['aws_instance_type']['label']                            = $this->lang['aws_instance_type'];
		$d['aws_instance_type']['object']['type']                   = 'htmlobject_select';
		$d['aws_instance_type']['object']['attrib']['index']   		= array(1, 0);
		$d['aws_instance_type']['object']['attrib']['name']         = 'aws_instance_type';
		$d['aws_instance_type']['object']['attrib']['id']           = 'aws_instance_type';
		$d['aws_instance_type']['object']['attrib']['type']         = 'text';
		$d['aws_instance_type']['object']['attrib']['value']        = $aws_instance_type;
		$d['aws_instance_type']['object']['attrib']['options']    	= $available_instance_types;
		
		$form->add($d);
		$response->form = $form;
		return $response;
	}
	
	function instancePackage(){
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
			$packagelist [] = array($temp[0] . " - " .$temp[1] . " CPU(s) " . $temp[2] . " GB RAM", $temp[0]);
		}
		return $packagelist;
	}

}
?>
