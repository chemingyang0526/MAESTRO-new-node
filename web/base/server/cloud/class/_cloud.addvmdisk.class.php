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

class addvmdisk {
/**
* name of action buttons
* @access public
* @var string
*/
var $actions_name = 'resource_action';
/**
* message param
* @access public
* @var string
*/
var $message_param = "resource_msg";
/**
* id for tabs
* @access public
* @var string
*/
var $prefix_tab = 'resource_tab';
/**
* identifier name
* @access public
* @var string
*/
var $identifier_name = 'resource_identifier';
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
		$this->response = $response;
		$this->file     = $htvcenter->file();
		$this->htvcenter  = $htvcenter;
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
		$data = $this->select();
		$t = $this->response->html->template($this->tpldir.'/cloud-addvmdisk.tpl.php');
		$t->add($this->response->html->thisfile, "thisfile");
		$t->add($data);
		$t->add($data->form);
		$t->add($this->lang['label'], 'label');
		$t->add($this->htvcenter->get('baseurl'), 'baseurl');
		$t->add($this->lang['lang_filter'], 'lang_filter');
		$t->add($this->lang['please_wait'], 'please_wait');
		$t->group_elements(array('param_' => 'form'));
		return $t;
	}

	//--------------------------------------------
	/**
	 * Select
	 *
	 * @access public
	 * @return array
	 */
	//--------------------------------------------
	function select() {
		if(isset($_GET)){
			$params = unserialize(urldecode($_GET['params']));
			$vm_memory = $params['memory'];
			$vm_cpu = $params['cpu'];
			$vm_operating_system = $params['os'];
			$vm_monthly_price = $params['monthly_price'];
			$vm_base = trim($_GET['platform']);
		}
		$cloud_price_dump = shell_exec('python '.$this->rootdir.'/server/cloud/script/awspriceparsing.py');
		$cloud_price = json_decode($cloud_price_dump, true);
		$memory = array();
		$operatingSystem = array();
		$vcpu = array();
		
		foreach($cloud_price as $k => $v){
			if(!in_array($v['memory'], $memory)){
				$memory[] = array($v['memory'], $v['memory']);
			}
			if(!in_array($v['operatingSystem'], $operatingSystem)){
				$operatingSystem[] = array($v['operatingSystem'], $v['operatingSystem']);
			}
			if(!in_array($v['vcpu'], $vcpu)){
				$vcpu[] = array($v['vcpu'], $v['vcpu']);
			}
		}
		$base = array(array( 'aws', 'aws' ), array('az', 'az') );
		$disk_volume = array(array('20', '20 GB'), array('50', '50 GB'), array('100', '100 GB'), array('200', '200 GB'), array('250', '250 GB'), array('500', '500 GB'), array('1000', '1000 GB (1 TB)'));
		
		sort($processorArchitecture);
		sort($memory);
		sort($operatingSystem);
		sort($vcpu);
		sort($clockSpeed);
		
		$d = array();	
		$html_information = "";
		
		$response = $this->response;
		$form = $response->get_form($this->actions_name, 'cloudprice');
		$submit = $form->get_elements('submit');
		$submit->handler = 'onclick="wait();';
		$submit->value = 'Create VM';
		$form->add($submit, 'submit');

		$submit = $form->get_elements('cancel');
		$submit->handler = 'onclick="cancel();"';
		$form->add($submit, 'cancel');
		
		
		$d['base']['label']												= $this->lang['base'];
		$d['base']['object']['type']									= 'htmlobject_select';
		$d['base']['object']['attrib']['index']							= array(0, 1);
		$d['base']['object']['attrib']['name']							= 'base';
		$d['base']['object']['attrib']['id']							= 'base';
		$d['base']['object']['attrib']['type']							= 'text';
		$d['base']['object']['attrib']['options']						= $base;
		$d['base']['object']['attrib']['selected']						= array($vm_base);
		
		$d['memory']['label']											= $this->lang['memory'];
		$d['memory']['object']['type']									= 'htmlobject_select';
		$d['memory']['object']['attrib']['index']						= array(0, 1);
		$d['memory']['object']['attrib']['name']						= 'memory';
		$d['memory']['object']['attrib']['id']							= 'memory';
		$d['memory']['object']['attrib']['type']						= 'text';
		$d['memory']['object']['attrib']['options']						= $memory;
		$d['memory']['object']['attrib']['selected']					= array($vm_memory);
		
		$d['operating_system']['label']									= $this->lang['operating_system'];
		$d['operating_system']['object']['type']						= 'htmlobject_select';
		$d['operating_system']['object']['attrib']['index']				= array(0, 1);
		$d['operating_system']['object']['attrib']['name']				= 'operating_system';
		$d['operating_system']['object']['attrib']['id']				= 'operating_system';
		$d['operating_system']['object']['attrib']['type']				= 'text';
		$d['operating_system']['object']['attrib']['options']			= $operatingSystem;
		$d['operating_system']['object']['attrib']['selected']			= array($vm_operating_system);
		
		$d['vcpu']['label']												= $this->lang['vcpu'];
		$d['vcpu']['object']['type']									= 'htmlobject_select';
		$d['vcpu']['object']['attrib']['index']							= array(0, 1);
		$d['vcpu']['object']['attrib']['name']							= 'vcpu';
		$d['vcpu']['object']['attrib']['id']							= 'vcpu';
		$d['vcpu']['object']['attrib']['type']							= 'text';
		$d['vcpu']['object']['attrib']['options']						= $vcpu;
		$d['vcpu']['object']['attrib']['selected']						= array($vm_cpu);
		
		$d['disk_volume']['label']										= $this->lang['disk_volume'];
		$d['disk_volume']['object']['type']								= 'htmlobject_select';
		$d['disk_volume']['object']['attrib']['index']					= array(0, 1);
		$d['disk_volume']['object']['attrib']['name']					= 'disk_volume';
		$d['disk_volume']['object']['attrib']['id']						= 'disk_volume';
		$d['disk_volume']['object']['attrib']['type']					= 'text';
		$d['disk_volume']['object']['attrib']['options']				= $disk_volume;
		$d['disk_volume']['object']['attrib']['selected']				= array($vm_cpu);
		
		$disk_volume_type = array(
			array("gp2", "Amazon EBS General Purpose SSD (gp2) volumes - $0.10 per GB-month"),
			array("io1", "Amazon EBS Provisioned IOPS SSD (io1) volumes - $0.125 per GB-month, $0.065 per provisioned IOPS-month"),
			array("st1", "Amazon EBS Throughput Optimized HDD (st1) volumes - $0.045 per GB-month"),
			array("sc1", "Amazon EBS Cold HDD (sc1) volumes - $0.025 per GB-month"),
		);
		
		$d['disk_volume_type']['label']										= $this->lang['disk_volume'];
		$d['disk_volume_type']['object']['type']							= 'htmlobject_select';
		$d['disk_volume_type']['object']['attrib']['index']					= array(0, 1);
		$d['disk_volume_type']['object']['attrib']['name']					= 'disk_volume_type';
		$d['disk_volume_type']['object']['attrib']['id']					= 'disk_volume_type';
		$d['disk_volume_type']['object']['attrib']['type']					= 'text';
		$d['disk_volume_type']['object']['attrib']['options']				= $disk_volume_type;
		
		$d['vm_monthly_price']['label']                             	= $this->lang['vcpu'];
		$d['vm_monthly_price']['object']['type']                    	= 'htmlobject_input';
		$d['vm_monthly_price']['object']['attrib']['name']          	= 'vm_monthly_price';
		$d['vm_monthly_price']['object']['attrib']['id']            	= 'vm_monthly_price';
		$d['vm_monthly_price']['object']['attrib']['type']          	= 'text';
		$d['vm_monthly_price']['object']['attrib']['value']         	= $vm_monthly_price;
		$d['vm_monthly_price']['object']['attrib']['maxlength']     	= 150;

		
		$d['html_information']  = $html_information;
		
		$form->add($d);
		$response->form = $form;
		return $response;
	}
}
?>
