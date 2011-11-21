<?php

/**
 * Config class, Reads the configfile.
 *
 * @author Elis Axelsson <elis.axelsson@gmail.com>
 * @copyright GNU Public License v3
 * @package EtuFW
 */

class Config {
	protected $database;
	protected $routing;
	
	public function __construct() {
		$cfg = parse_ini_file('../config.ini', true);
		
		$this->database = $cfg['Database'];
		$this->routing  = $cfg['Routing'];
	}
	
	public function getDatabase() {
		return $this->database;
	}
	
	public function getRouting() {
		return $this->routing;
	}
}

