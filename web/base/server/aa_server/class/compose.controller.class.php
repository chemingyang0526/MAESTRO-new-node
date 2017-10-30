<?php
/**
 * Datacenter Controller
 *
    htvcenter Enterprise developed by HTBase Corp.

    All source code and content (c) Copyright 2015, HTBase Corp unless specifically noted otherwise.

    This source code is released under the htvcenter Enterprise Server and Client License, unless otherwise agreed with HTBase Corp.

    By using this software, you acknowledge having read this license and agree to be bound thereby.

                http://www.htbase.com

    Copyright 2015, HTBase Corp <contact@htbase.com>
 */

class compose_controller
{
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
* identifier name
* @access public
* @var string
*/
var $identifier_name = 'compose_identifier';
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
var $lang = array(
	'compose' => array (
		'tab' => 'Datacenters',
		'label' => 'Datacenters',
		'title' => 'htvcenter Datacenter Dashboard',
		'load_headline' => 'Datacenter Load',
		'load_current' => 'current',
		'load_last_hour' => 'last hour',
		'inventory_headline' => 'Inventory',
		'inventory_servers' => 'Server by type',
		'inventory_storages' => 'Storage Pool',
		'events_headline' => 'Events',
		'events_date' => 'Date',
		'events_source' => 'Source',
		'events_description' => 'Description',
		'datacenter_load_overall' => 'Datacenter <small>(overall)</small>',
		'appliance_load_overall' => 'Server',
		'storage_load_overall' => 'Storage',
		'link_server_management' => 'Server Management',
		'link_storage_management' => 'Storage Management',
		'no_data_available' => 'No Data available',
		'please_wait' => 'Loading. Please wait ..',
	),
	'addcompose' => array (
		'tab' => 'Add Compose',
		'label' => 'Add Compose',
		'compose_type' => 'Type',
		'composed_name' => 'Composed Name',
	),
);

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
		$this->htvcenter  = $htvcenter;
		$this->user     = $this->htvcenter->user();
		$this->rootdir  = $this->htvcenter->get('webdir');
		$this->tpldir   = $this->rootdir.'/server/aa_server/tpl';
		$this->response = $response;
		$this->file     = $this->htvcenter->file();
		$this->lang     = $this->user->translate($this->lang, $this->rootdir."/server/aa_server/lang", 'datacenter.ini');
		require_once $this->rootdir."/include/htvcenter-database-functions.php";
		$this->db 				= htvcenter_get_db_connection();
		
		if(isset($_GET['request'])) {
			$host_array = array();
			$resource = new resource();
			$resources = $resource->display_overview(0, 10000, 'resource_id', 'ASC');
			
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
			
			if($_GET['profile'] == "local"){
				foreach ($resources as $index => $resource_db) {
					$resource = new resource();
					$resource->get_instance_by_id($resource_db["resource_id"]);
					$res_id = $resource->id;
					if( ($resource->id == $resource->vhostid) && ($resource->id != 0) && !in_array($resource->id, $compose_resource_array)){
						$virtualization = new virtualization();
						$virtualization->get_instance_by_id($resource->vtype);
						
						$memInGB = ($resource_db['resource_memtotal'] / 1024);
						$memInGB = number_format((float) $memInGB, 2, '.', '');
						
						if ($_GET['request'] == 'och'){
							if($virtualization->name == "KVM Host" || $virtualization->name == "OCH Host"){
								$host_array[] = array('resource_id' => $resource->id, 'resource_hostname' => $resource_db['resource_hostname'], 'resource_memtotal' => $memInGB, 'resource_cpunumber' => $resource_db['resource_cpunumber'], 'virtualization_name' => $virtualization->name);
							}
						} else if ($_GET['request'] == 'vmware'){
							if($virtualization->name == "ESX Host" || $virtualization->name == "VMware Host"){
								$host_array[] = array('resource_id' => $resource->id, 'resource_hostname' => $resource_db['resource_hostname'], 'resource_memtotal' => $memInGB, 'resource_cpunumber' => $resource_db['resource_cpunumber'], 'virtualization_name' => $virtualization->name);
							}
						} else if ($_GET['request'] == 'physical'){
							if($virtualization->name == "Physical System"){
								$host_array[] = array('resource_id' => $resource->id, 'resource_hostname' => $resource_db['resource_hostname'], 'resource_memtotal' => $memInGB, 'resource_cpunumber' => $resource_db['resource_cpunumber'], 'virtualization_name' => $virtualization->name);
							}
						} else if ($_GET['request'] == 'vsphere'){
							if($virtualization->name == "vSphere Host"){
								$host_array[] = array('resource_id' => $resource->id, 'resource_hostname' => $resource_db['resource_hostname'], 'resource_memtotal' => $memInGB, 'resource_cpunumber' => $resource_db['resource_cpunumber'], 'virtualization_name' => $virtualization->name);
							}
						} else {
							$host_array[] = array('resource_id' => $resource->id, 'resource_hostname' => $resource_db['resource_hostname'], 'resource_memtotal' => $memInGB, 'resource_cpunumber' => $resource_db['resource_cpunumber'], 'virtualization_name' => $virtualization->name);
						}
					}
				}
			} else if($_GET['profile'] == "cloud") {
				if ($_GET['request'] == 'aws') {
					$ec2_info_dump = shell_exec('python '.$this->rootdir.'/server/aa_server/js/cloudvms.py aws');
					$ec2_info = json_decode($ec2_info_dump, true);
					foreach($ec2_info as $k => $v) {
						$temp = explode("_", $v);
						if(!in_array($temp[0], $compose_resource_array)) {
							$host_array[] = array('resource_id' => $temp[0], 'resource_hostname' => $temp[0] . " (".$temp[1]. ")", 'resource_memtotal' => $temp[7], 'resource_cpunumber' => $temp[6], 'virtualization_name' => 'AWS Instance - '.$temp[5]);
						}
					}
				} else if ($_GET['request'] == 'az') {
					$ec2_info_dump = shell_exec('python '.$this->rootdir.'/server/aa_server/js/cloudvms.py az');
					$ec2_info = json_decode($ec2_info_dump, true);
					foreach($ec2_info as $k => $v) {
						$temp = explode("_", $v);
						$memInGB = ($temp[2] / 1024);
						$memInGB = number_format((float) $memInGB, 2, '.', '');
						if(!in_array($temp[0], $compose_resource_array)) {
							$host_array[] = array('resource_id' => $temp[0], 'resource_hostname' => $temp[0], 'resource_memtotal' => $memInGB, 'resource_cpunumber' => $temp[1], 'virtualization_name' => 'Azure VM');
						}
					}
				} 
			}
			echo json_encode($host_array);
			die();
		}
		
		if(isset($_GET['dbinsert'])) {
			$data_array = array();
			if(isset($_GET['maestroComposeName'])){
				$maestroComposeName = $_GET['maestroComposeName'];
			} else {
				$maestroComposeName = "Compose name did not found";
			}
			$data_array['compose_name'] = $maestroComposeName;
			
			if(isset($_GET['maestroComposeType'])){
				$maestroComposeType = $_GET['maestroComposeType'];
				
			} else {
				$maestroComposeType = $_GET['maestroComposeType'];
			}
			$data_array['compose_type'] = $maestroComposeType;
			
			if(isset($_GET['composeTotalMemory'])){
				$composeTotalMemory = $_GET['composeTotalMemory'];
			} else {
				$composeTotalMemory = "Total memory did not found";
			}
			$data_array['total_memory'] = $composeTotalMemory;
			
			if(isset($_GET['composeTotalCpu'])){
				$composeTotalCpu = $_GET['composeTotalCpu'];
			} else {
				$composeTotalCpu = "Total CPU did not found";
			}
			$data_array['total_cpu'] = $composeTotalCpu;
			$compose_appliances = $_GET['applianceID'];
			
			$RootDir = $_SERVER["DOCUMENT_ROOT"].'/htvcenter/base/';
			$BootServiceDir = $_SERVER["DOCUMENT_ROOT"].'/htvcenter/boot-service/';
			require_once "$RootDir/include/htvcenter-database-functions.php";
			
			$db = htvcenter_get_db_connection();
			$dbSql = $db->Execute("INSERT INTO `maestro_compose` (`compose_name`, `compose_type`, `compose_memory`, `compose_cpu`, `compose_appliances`, `create_date`, `edit_date`, `compose_status`) VALUES ('".$maestroComposeName."', '".$maestroComposeType."', '".$composeTotalMemory."', '".$composeTotalCpu."', '".$compose_appliances."', '".date('Y-m-d')."', '0000-00-00', '1')");
			echo json_encode($data_array);
			die();
		}
		
		if(isset($_GET['composeData'])) {
			$dbSql = $this->db->GetAll("SELECT * FROM `maestro_compose`");
			echo json_encode($dbSql);
			die();
		}
		
		if(isset($_GET['enabledPlugin'])) {
			$plugin = new plugin();
			$enabled_plugins = $plugin->enabled();
			$pluginArray = array();
			foreach($enabled_plugins as $plugin){
				if($plugin == 'vmware-vsphere') {
					$pluginArray[] = 'VSphere';
				} else if($plugin == 'vmware-esx') {
					$pluginArray[] = 'ESX';
				} else if($plugin == 'kvm') {
					$pluginArray[] = 'OCH';
				}
			}
			echo json_encode($pluginArray); die();
		}
		
		if(isset($_GET['checkuname'])) {
			$dbSql = $this->db->GetAll("SELECT * FROM `maestro_compose` WHERE compose_name = '".trim($_GET['checkuname'])."'");
			if (count($dbSql) > 0 ){
				echo false;
			} else {
				echo true;
			}
			die();
		}
	}

	//--------------------------------------------
	/**
	 * Action
	 *
	 * @access public
	 * @param string $action
	 * @return htmlobject_tabmenu
	 */
	//--------------------------------------------
	function action($action = null) {
		$this->action = '';
		$ar = $this->response->html->request()->get($this->actions_name);
		if($ar !== '') {
			$this->action = $ar;
		} 
		else if(isset($action)) {
			$this->action = $action;
		}
		if($this->response->cancel()) {
			$this->action = "compose";
		}

		$content = array();
		switch( $this->action ) {
			case '':
			case 'compose':
				$content[] = $this->compose(true);
			break;
			case 'editcompose':
				$content[] = $this->compose(false);
				$content[] = $this->editcompose(true);
			break;
			case 'deletecompose':
				$content[] = $this->compose(false);
				$content[] = $this->deletecompose(true);
			break;
		}

		$tab = $this->response->html->tabmenu($this->prefix_tab);
		$tab->message_param = $this->message_param;
		$tab->css = 'htmlobject_tabs';
		$tab->add($content);
		return $tab;
	}

	//--------------------------------------------
	/**
	 * API
	 *
	 * @access public
	 */
	//--------------------------------------------
	function api() {
		require_once($this->rootdir.'/server/aa_server/class/datacenter.api.class.php');
		$controller = new datacenter_api($this);
		$controller->action();
	}

	
	//--------------------------------------------
	/**
	 * Datacenter Dashboard
	 *
	 * @access public
	 * @param bool $hidden
	 * @return array
	 */
	//--------------------------------------------
	function compose( $hidden = true ) {
		$data = '';
		if( $hidden === true ) {
			require_once($this->rootdir.'/server/aa_server/class/compose.dashboard.class.php');
			$controller = new compose_dashboard($this->htvcenter, $this->response);
			$controller->actions_name    = $this->actions_name;
			$controller->tpldir          = $this->tpldir;
			$controller->message_param   = $this->message_param;
			$controller->identifier_name = $this->identifier_name;
			$controller->lang            = $this->lang['compose'];
			$data = $controller->action();
		}
		$content['label'] = $this->lang['compose']['tab'];
		$content['value'] = $data;
		$content['target'] = $this->response->html->thisfile;
		$content['request'] = $this->response->get_array($this->actions_name, 'compose' );
		$content['onclick'] = false;
		if($this->action === 'compose'){
			$content['active']  = true;
		}

		//$content = "<p>Compose</p>";
		return $content;
	}
	
	//--------------------------------------------
	/**
	 * Datacenter Dashboard
	 *
	 * @access public
	 * @param bool $hidden
	 * @return array
	 */
	//--------------------------------------------
	function editcompose( $hidden = true ) {
		$data = '';
		if( $hidden === true ) {
			require_once($this->rootdir.'/server/aa_server/class/compose.add.class.php');
			$controller = new addcompose($this->htvcenter, $this->response);
			$controller->actions_name    = $this->actions_name;
			$controller->tpldir          = $this->tpldir;
			$controller->message_param   = $this->message_param;
			$controller->identifier_name = $this->identifier_name;
			$controller->lang            = $this->lang['compose'];
			$data = $controller->action();
		}
		$content['label'] = $this->lang['addcompose']['tab'];
		$content['value'] = $data;
		$content['target'] = $this->response->html->thisfile;
		$content['request'] = $this->response->get_array($this->actions_name, 'editcompose' );
		$content['onclick'] = false;
		if($this->action === 'editcompose'){
			$content['active']  = true;
		}

		//$content = "<p>addcompose</p>";
		return $content;
	}

	//--------------------------------------------
	/**
	 * Delete entry
	 *
	 * @access public
	 * @param bool $hidden
	 * @return array
	 */
	//--------------------------------------------
	function deletecompose( $hidden = true ) {
		$id = $_GET['composeID'];
		$dbSql = $this->db->Execute("DELETE FROM `maestro_compose` WHERE id = " . $id);
		
		if($dbSql) {
			$response->msg = sprintf("Compose Server deleted successfully");
		} else {
			$response->msg = sprintf("Failed to delete compose server");
		}
		if(isset($response->msg)) {
			$this->response->redirect(
				$this->response->get_url('', '', $this->message_param, $response->msg)
			);
		}
	}

}
?>
