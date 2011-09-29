<?php

class Controller extends BasicObject {
	
	public function __construct(UriParser &$uri, Db &$db) {
		$structure = array('name' => 'string', 'controller' => 'Object');
		parent::__construct($db, $structure, True);
		
		
		$this->data['controller'] = new stdClass;
		
		if($uri->page === '')
			$this->data['name'] = 'Start';
		else
			$this->data['name'] = ucfirst($uri->page);
		
		
		$file = '../controllers/'.strtolower($this->data['name']).'.php';
		if(file_exists($file)) {
			require_once($file);
			
			$name = 'Controller'.$this->data['name'];
			$this->data['controller'] = new $name($uri, $this->db);
			
			$action = $uri->action;
			if($action === '')
				$this->data['controller']->index();
			else
				$this->data['controller']->$action();
			
		} else
			throw new Exception('No controller named: '.$this->data['name']);
	}
	
	public function __get($key) {
		return $this->controller->$key;
	}
	
	public function __set($key, $value) {
		return $this->controller->$key = $value;
	}
}
