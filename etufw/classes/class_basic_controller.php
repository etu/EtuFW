<?php

/**
 * BasicController class, Baseclass for all Controllers, handles View loading and Model loading.
 *
 * @author Elis Axelsson <elis.axelsson@gmail.com>
 * @copyright GNU Public License v3
 * @package EtuFW
 */

class BasicController extends BasicObject {
	/**
	 * Construction of class...
	 * 
	 * @param $uri The UriParser contains much usefull info to send on from here
	 * @param $orm The Orm instance is never bad to have
	 */
	public function __construct(UriParser &$uri, Orm &$orm) {
		$structure = array('uri' => 'Object');
		parent::__construct($orm, $structure, False);
		
		$this->uri = $uri;
	}
	
	/**
	 * Handles loading of Model objects
	 * 
	 * @param $name modelname
	 * @returns A Model Object
	 */
	protected function model($name) {
		$file = '../models/'.strtolower($name).'.php';
		if(file_exists($file)) {
			require_once($file);
			
			$modelname = 'Model'.ucfirst($name);
			
			return new $modelname($this->orm);
		} else
			throw new Exception('No Model named: '.$name);
	}
	
	/**
	 * Handles loading of the View object
	 * 
	 * @param $name Name of your viewfile of choice
	 * @returns A View object prepared with your viewfile
	 */
	protected function view($name) {
		return new View($name, $this->orm);
	}
}
