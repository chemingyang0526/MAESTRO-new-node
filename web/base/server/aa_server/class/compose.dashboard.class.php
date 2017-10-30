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

class compose_dashboard {
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
	$this->response			= $response;
	$this->file 			= $htvcenter->file();
	$this->htvcenter		= $htvcenter;
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
	$t = $this->response->html->template($this->tpldir.'/compose-dashboard.tpl.php');
	$t->add($this->lang['title'], 'title');
	$t->add($this->lang['load_headline'], 'load_headline');
	$t->add($this->lang['no_data_available'], 'no_data_available');
	
	$dbSql = $this->db->GetAll("SELECT * FROM `maestro_compose`");

	$div_html = '';
	$row_headers = array('ID', 'Compose Name', 'Type', 'Total Memory', 'CPU', 'Hosts', 'Status', '...');
	$div_html = '<table class="table table-hover nowrap dataTable dtr-inline" id="maestro_composed_table" role="grid" style="width: 100%;"><thead><tr>';
	foreach ($row_headers as $head) {
		$div_html .= '<th>'.$head.'</th>';
	}
	//echo $this->getApplianceName(15058513351058);
	//remaining fields: create_date edit_date compose_status
	$div_html .= '</tr></thead><tbody>';
	for ($i = 0; $i < count($dbSql); $i++) {
		$compose_type = $dbSql[$i]['compose_type'];
		$comp_type = explode(",", $compose_type);
		
		$appTemp = explode(",", $dbSql[$i]['compose_appliances']);
		$appName = "";
		$count = 1;
		$memInGB = $dbSql[$i]['compose_memory'];
		
		/*if($comp_type[0] == "cloud") {
			$appName = $comp_type[1];
		} else {
			foreach($appTemp as $app){
				if(count($appTemp) == $count){
					$appName = $appName . $this->getApplianceName($app);
				} else {
					$appName = $appName . $this->getApplianceName($app) . "<br /> ";
				}
				$count++;
			}
		}*/
		
		$appName = $comp_type[1];
		
		$composeStatus = "";
		if($dbSql[$i]['compose_status'] == 1) {
			$composeStatus = '<div class="compose-status compose-active">active</div>';
		} else {
			$composeStatus = '<div class="compose-status compose-inactive">inactive</div>';
		}
		
		$div_html .= '<tr class="hoverbg" id="' . $i . '">';
		$div_html .= '<td>' . $dbSql[$i]['id'] . '</td>';
		$div_html .= '<td>' . $dbSql[$i]['compose_name'] . '</td>';
		$div_html .= '<td>' .  ucwords($comp_type[0]) . '</td>';
		$div_html .= '<td>' .  $this->gbtoTBConversion($memInGB) . '</td>';
		$div_html .= '<td>' .  $dbSql[$i]['compose_cpu'] . '</td>';
		$div_html .= '<td>' .  $appName . '</td>';
		$div_html .= '<td>' .  $composeStatus . '</td>';
		$div_html .= '<td class="toggle-graph" row-id="' . $i . '"><a href="#"><i class="fa fa-ellipsis-h" aria-hidden="true"></i></a></td>';
		$div_html .= '</tr>'; 
	}
	$div_html .=	'</tbody></table>';
	
	$t->add($div_html, 'compose_servers');
	return $t;
}

function getApplianceName($id){
	$appSql = $this->db->GetAll("SELECT resource_hostname FROM resource_info WHERE resource_id=".$id);
	return $appSql[0]['resource_hostname'];
}

function get_response($mode = '') {
	$response = $this->response;
	return $response;
}

function gbtoTBConversion($bytes, $precision = 2) {
	$gigabyte = 1024;
	$terabyte = $gigabyte * 1024;
	if (($bytes >= 0) && ($bytes < $gigabyte)) {
		return $bytes . ' GiB';
	} elseif (($bytes >= $gigabyte) && ($bytes < $terabyte)) {
		return round($bytes / $gigabyte, $precision) . ' TiB';
	} else {
		return $bytes . ' B';
	}
}

}
?>