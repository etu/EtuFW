<?php

class View extends BasicObject {
	public function __construct($name, Db &$db) {
		$structure = array('data' => 'array', 'name' => 'string');
		parent::__construct($db, $structure, True);
		
		$this->data['name'] = strtolower($name);
		$this->data['data'] = array();
		
		$file = '../views/'.$this->name.'.php';
		if(!file_exists($file))
			throw new Exception('No view named: '.$name);
		
		$this->data['file'] = $file;
	}
	
	public function addData($key, $value) {
		$this->data['data'][$key] = $value;
	}
	
	public function render() {
		if(count($this->data['data']) >= 1) ///< @todo This block causes 500 internal server error if I don't give it data at all
			foreach($this->data['data'] as $key => $value)
				$$key = $value;
		
		$_SERVER['DOCUMENT_ROOT'] = str_replace('http', 'views', $_SERVER['DOCUMENT_ROOT']); ///< @todo replace the end of the string, not in the string
		
		$_view_file = $this->data['file'];
		unset($this);
		
		include($_view_file);
	}
}
