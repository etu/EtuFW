<?php

/**
 * Config class, Reads the configfile.
 *
 * @author Elis Axelsson <elis.axelsson@gmail.com>
 * @copyright GNU Public License v3
 * @package EtuFW
 */

class Config {
	protected $database; ///< Contains the database options
	protected $global;   ///< Contains the global options
	protected $routing;  ///< Contains the routing options
	
	/**
	 * Contstruction of Config, reads the ../config.ini file and parses it.
	 */
	public function __construct() {
		$cfg = parse_ini_file('../config.ini', true);
		
		$this->database = $cfg['Database'];
		$this->global   = $cfg['Global'];
		$this->routing  = $cfg['Routing'];
	}
	
	/**
	 * @returns Array with Database configs
	 */
	public function getDatabase() {
		return $this->database;
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

