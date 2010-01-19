<?php 

/*
 * othAuth By othman ouahbi.
 * comments, bug reports are welcome crazylegs AT gmail DOT com
 * @author CraZyLeGs
 * @version 0.1.2 
 * @license MIT
 */
class othAuthHelper extends Helper {
	var $helpers = array (
		'Session'
	);
	var $hashkey = "test";

	var $initialized = false;

	function init() {

		if (!$this->initialized) {
			if (!isset ($this->view)) {
				$this->view = & ClassRegistry :: getObject('view');
			}
			if (!empty ($this->view->viewVars['othAuth_data'])) {
				$data = $this->view->viewVars['othAuth_data'];

				foreach ($data as $k => $v) {
					$this-> $k = $v;
				}

				//$this->us = 'othAuth.'.$this->hashkey;
			}
			$this->initialized = true;
		}

	}

	function sessionValid() {
		$this->init();
		return ($this->Session->check('othAuth.' . $this->hashkey));
	}
	// helper methods
	function user($arg) {
		$this->init();
		// does session exists
		if ($this->sessionValid()) {
			$ses = $this->Session->read('othAuth.' . $this->hashkey);

			if (isset ($ses["{$this->user_model}"][$arg])) {
				return $ses["{$this->user_model}"][$arg];
			} else {
				return false;
			}
		}
		return false;

		//return $this->view->controller->othAuth->user($arg);
	}

	// helper methods
	function group($arg) {

		$this->init();
		// does session exists
		if ($this->sessionValid()) {
			$ses = $this->Session->read('othAuth.' . $this->hashkey);
			if (isset ($ses["{$this->group_model}"][$arg])) {
				return $ses["{$this->group_model}"][$arg];
			} else {
				return false;
			}
		}
		return false;

		//return $this->view->controller->othAuth->group($arg);
	}

	// helper methods
	function permission($arg) {

		$this->init();
		// does session exists
		if ($this->sessionValid()) {
			$ses = $this->Session->read('othAuth.' . $this->hashkey);
			if (isset ($ses[$this->group_model][$this->permission_model])) {
				$ret = array ();
				if (is_array($ses[$this->group_model][$this->permission_model])) {
					for ($i = 0; $i < count($ses[$this->group_model][$this->permission_model]); $i++) {
						$ret[] = $ses[$this->group_model][$this->permission_model][$i][$arg];
					}
				}
				return $ret;
			} else {
				return false;
			}
		}
		return false;

		//return $this->view->controller->othAuth->permission($arg);
	}

	function getData($arg = '', $only = true) {
		// does session exists
		if ($this->sessionValid()) {
			$data = $this->Session->read('othAuth.' . $this->hashkey);
			$arg = strtolower($arg);

			if ($arg == 'user') {
				$data = $data['User'];

			}
			elseif ($arg == 'group') {
				if ($only) {
					unset ($data['Group']['Permission']);
				}

				$data = $data['Group'];

			}
			elseif ($arg == 'permission') {
				$data = $data['Group']['Permission'];
			}

			return $data;
		}
		return false;
	}
	
	function hasPermission($val) {
		$perms = $this->permission('name');
		if (in_array('*', $perms)) {
			return true;
		}
		if (in_array($val, $perms)) {
			return true;
		}

		$vals = explode('/', $val);
		$val = '';
		for ($i = 0; $i < count($vals); $i++) {
			if ($i) {
				$val .= '/';
			}
			$val .= $vals[$i];
			if (in_array($val, $perms)) {
				return true;
			}
		}
		return false;
	}

}
?>