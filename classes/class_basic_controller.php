<?php

class BasicController extends BasicObject {
	public function __construct(UriParser &$uri, Db &$db) {
		$structure = array('uri' => 'Object');
		parent::__construct($db, $structure, False);
		
		$this->uri = $uri;
	}
	
	public function model($name) {
		$file = '../models/'.strtolower($name).'.php';
		if(file_exists($file)) {
			require_once($file);
			
			$modelname = 'Model'.ucfirst($name);
			
			return new $modelname;
		} else
			throw new Exception('No Model named: '.$name);
	}
	
	public function view($name) {
		return new View($name, $this->db);
	}
}
