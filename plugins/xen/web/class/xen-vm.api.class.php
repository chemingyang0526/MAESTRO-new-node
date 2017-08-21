<?php
/**
 * xen storage vm api
 *
    htvcenter Enterprise developed by htvcenter Enterprise GmbH.

    All source code and content (c) Copyright 2012, htvcenter Enterprise GmbH unless specifically noted otherwise.

    This source code is released under the htvcenter Enterprise Server and Client License, unless otherwise agreed with htvcenter Enterprise GmbH.
    The latest version of this license can be found here: http://htvcenter-enterprise.com/license

    By using this software, you acknowledge having read this license and agree to be bound thereby.

                http://htvcenter-enterprise.com

    Copyright 2012, htvcenter Enterprise GmbH <info@htvcenter-enterprise.com>
 */

class xen_vm_api
{
	//--------------------------------------------
	/**
	 * Constructor
	 *
	 * @access public
	 * @param object $controller
	 */
	//--------------------------------------------
	function __construct($controller) {
		$this->controller = $controller;
		$this->user       = $this->controller->user;
		$this->html       = $this->controller->response->html;
		$this->response   = $this->html->response();
		$this->file       = $this->controller->file;
		$id = $this->response->html->request()->get('appliance_id');
		if($id === '') {
			return false;
		}
		// set ENV

		$this->response->params['appliance_id'] = $id;

		$appliance = new appliance();
		$resource  = new resource();

		$appliance->get_instance_by_id($id);
		$resource->get_instance_by_id($appliance->resources);

		$this->resource  = $resource;
		$this->appliance = $appliance;
		$this->statfile  = $this->controller->htvcenter->get('basedir').'/plugins/xen/web/xen-stat/'.$resource->id.'.pick_iso_config';
	}

	//--------------------------------------------
	/**
	 * Action
	 *
	 * @access public
	 */
	//--------------------------------------------
	function action() {
		$action = $this->html->request()->get($this->controller->actions_name);
		switch( $action ) {
			case 'filepicker':
				$this->filepicker();
			break;
			case 'progress':
				$this->progress();
			break;
		}
	}

	//--------------------------------------------
	/**
	 * Filepicker
	 *
	 * @access public
	 */
	//--------------------------------------------
	function filepicker() {
		$response = $this->response;
		$iso_path = $response->html->request()->get('path');
		if ($iso_path !== '') {

			$command  = $this->controller->htvcenter->get('basedir')."/plugins/xen/bin/htvcenter-xen-vm iso";
			$command .= ' -q '.$iso_path;
			$command .= ' -u '.$GLOBALS['htvcenter_ADMIN']->name.' -p '.$GLOBALS['htvcenter_ADMIN']->password;
			$command .= ' --htvcenter-ui-user '.$this->user->name;
			$command .= ' --htvcenter-cmd-mode background';

			$file = $this->controller->htvcenter->get('basedir').'/plugins/xen/web/xen-stat/'.$this->resource->id.'.pick_iso_config';

#echo $file;
#exit(0);

			if($this->file->exists($file)) {
				$this->file->remove($file);
			}
			$this->resource->send_command($this->resource->ip, $command);
			while (!$this->file->exists($file)) // check if the data file has been modified
			{
			  usleep(10000); // sleep 10ms to unload the CPU
			  clearstatcache();
			}
			$lines = $this->file->get_contents($file);
			$lines = explode("\n", $lines);

			if(is_array($lines) && count($lines) > 1) {
				$i = 0;
				foreach($lines as $c) {
					$tmp = explode('@', $c);
					if($tmp[0] === 'P') {
						$base = $tmp[1];
					} else {
						if(isset($tmp[1]) && $tmp[1] !== '.') {
							$a  = $response->html->a();
							$a->label = $tmp[1];
							if($tmp['1'] === '..') {
								if($base !== '/') {
									$path = substr($base, 0, strrpos($base,'/'));
									if($path === '') {
										$path = '/';
									}
									$a->href = '#';
									$a->handler = 'onclick="filepicker.browse(\''.$path.'\'); return false;"';
									$a->css  = 'folder';
									$body[$i]['file'] = $c;
									$body[$i]['name'] = $a->get_string();
									$i++;
								}
							} else {
								if($base !== '/') {
									$tmp[1] = '/'.$tmp[1];
								}
								if($tmp[0] === 'F') {
									$a->handler = 'onclick="filepicker.insert(\''.$base.$tmp[1].'\'); return false;"';
									$a->href = '#';
									$a->css  = 'file';
								}
								if($tmp[0] === 'D') {
									$a->href = '#';
									$a->handler = 'onclick="filepicker.browse(\''.$base.$tmp['1'].'\'); return false;"';
									$a->css  = 'folder';
								}
								$body[$i]['file'] = $c;
								$body[$i]['name'] = $a->get_string();
								$i++;
							}
						}
					}
				}
			}
			$table = $response->html->tablebuilder('xen_vm_api', $response);

			$head['file']['hidden']   = true;
			$head['file']['sortable'] = false;
			$head['name']['title'] = 'Name';
			$head['name']['map'] = 'file';

			$table->max          = count($body);
			$table->limit        = count($body);
			$table->offset       = 0;
			$table->order        = 'ASC';
			$table->form_action  = $response->html->thisfile;
			$table->form_method  = 'GET';
			$table->css          = 'filepicker_table';
			$table->border       = 1;
			$table->id           = 'Table';
			$table->head         = $head;
			$table->body         = $body;
			$table->sort         = 'file';
			$table->sort_form    = false;
			$table->sort_link    = false;
			$table->autosort     = true;
			$table->handler_tr   = null;

			$table = $table->get_object();
			unset($table->__elements['pageturn_head']);
			unset($table->__elements[0]);

			echo $table->get_string();
		} else {
			echo 'no';
		}

	}

	//--------------------------------------------
	/**
	 * Get progress
	 *
	 * @access public
	 */
	//--------------------------------------------
	function progress() {
		$name = basename($this->response->html->request()->get('name'));
		$file = $this->controller->htvcenter->get('basedir').'/plugins/xen/web/xen-stat/'.$name;
		if($this->file->exists($file)) {
			echo $this->file->get_contents($file);
		} else {
			header("HTTP/1.0 404 Not Found");
		}
	}


}
?>
