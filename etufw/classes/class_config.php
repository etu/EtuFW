<?php

/**
 * Config class, Reads the configfile.
 *
 * @author Elis Axelsson <elis.axelsson@gmail.com>
 * @copyright GNU Public License v3
 * @package EtuFW
 */

class Config {
	protected $database;            ///< Contains the database options
	protected $session;             ///< Contains the session options
	protected $global;              ///< Contains the global options
	protected $routing;             ///< Contains the routing options
	
	public static $instance = Null; ///< Contains instance of object
	
	/**
	 * Contstruction of Config, reads the ../config.ini file and parses it.
	 */
	protected function __construct() {
		$cfg = parse_ini_file(ETUFW.'/config.ini', true);
		
		$this->database = $cfg['Database'];
		$this->session  = $cfg['Session'];
		$this->global   = $cfg['Global'];
		$this->routing  = $cfg['Routing'];
		
		date_default_timezone_set($cfg['Global']['defaultTimezone']);
	}
	
	/**
	 * Singleton initiator for Config, use this to get the Config class
	 */
	public static function getInstance() {
		if(!isset(self::$instance)) {
			self::$instance = new Config();
		}
		
		return self::$instance;
	}
	
	/**
	 * @returns Array with Database configs
	 */
	public function getDatabase() {
		return $this->database;
	}
	
	/**
	 * @returns Array with Session configs
	 */
	public function getSession($key = '') {
		if(isset($this->session[$key])) {
			return $this->session[$key];
		} else {
			return $this->session;
		}
	}
	
	/**
	 * @returns Array with Global configs
	 */
	public function getGlobal() {
		return $this->global;
	}
	
	/**
	 * @returns Array with Routing configs
	 */
	public function getRouting() {
		return $this->routing;
	}
}

