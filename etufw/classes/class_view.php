<?php

/**
 * View class, handles data and renders a view file
 * 
 * @author Elis Axelsson <elis.axelsson@gmail.com>
 * @copyright GNU Public License v3
 * @package EtuFW
 */

class View extends BasicObject {
	/**
	 * Construction of class...
	 * 
	 * @param $name Name of the view you want to load
	 * @param $orm Orm instance
	 */
	public function __construct($name, Orm &$orm) {
		$structure = array('data' => 'array', 'name' => 'string');
		parent::__construct($orm, $structure, True);
		
		$this->data['name'] = strtolower($name);
		$this->data['data'] = array();
		
		$file = '../views/'.$this->name.'.php';
		if(!file_exists($file))
			throw new Exception('No view named: '.$name);
		
		$this->data['file'] = $file;
	}
	
	/**
	 * Add data you want the viewfile should be able to access, such as arrays, objects, strings and so on
	 * 
	 * @param $key Name of the variable as you want it
	 * @param $value Value of the variable
	 */
	public function addData($key, $value) {
		$this->data['data'][$key] = $value;
	}
	
	/**
	 * Just renders the page
	 */
	public function render() {
		$this->addData('_render_start', microtime());
		
		if(count($this->data['data']) >= 1)
			foreach($this->data['data'] as $key => $value)
				$$key = $value;
		
		$_SERVER['DOCUMENT_ROOT'] = preg_replace('/http\/$/', 'views/', $_SERVER['DOCUMENT_ROOT']);
		
		$_view_file = $this->data['file'];
		unset($this);
		
		include($_view_file);
	}
}
