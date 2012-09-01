<?php

/**
 * Controller class, handles the controller loading and launches the action
 * 
 * @author Elis Axelsson <elis.axelsson@gmail.com>
 * @copyright GNU Public License v3
 * @package EtuFW
 */

class Controller extends BasicObject {
	/**
	 * Construction of class... When this constructor is done with the class, the
	 * class will almost act like the controller itself.
	 * 
	 * @param $uri The UriParser
	 * @param $orm The Orm instance
	 * @param $cfg The Config instance
	 */
	public function __construct(UriParser &$uri, Orm &$orm, Config &$cfg) {
		$structure = array('name' => 'string', 'controller' => 'Object');
		parent::__construct($orm, $structure, True);
		
		$global = $cfg->getGlobal();
		
		if($uri->page === '') // Select page
			$this->data['name'] = ucfirst($global['defaultController']);
		else
			$this->data['name'] = ucfirst($uri->page);
		
		$file = '../controllers/'.strtolower($this->data['name']).'.php'; // Build filename for page
		if(file_exists($file)) { // If it exists... include it
			require_once($file);
			
			$name = 'Controller'.$this->data['name'];
			$this->data['controller'] = new $name($uri, $this->orm); // Init the controller class
			
			if($uri->action === '') // Select method
				$action = $global['defaultMethdod'];
			else
				$action = $uri->action;
			
			if(in_array($action, get_class_methods($this->data['controller']))) // If method exists... run it
				$this->data['controller']->$action();
			else
				throw new Exception('No action named: '.$action);
			
		} else
			throw new Exception('No controller named: '.$this->data['name']);
	}
	
	/**
	 * Reimplemented to point to the controller inside the child
	 * 
	 * @see BasicObject::__get
	 */
	public function __get($key) {
		return $this->controller->$key;
	}
	
	/**
	 * Reimplemented to point to the controller inside the child
	 * 
	 * @see BasicObject::__set
	 */
	public function __set($key, $value) {
		return $this->controller->$key = $value;
	}
}
