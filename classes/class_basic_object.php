<?php

/**
 * BasicObject class, gives custom getters, setters and Db init stuff for all classes
 *
 * @author Elis Axelsson <elis.axelsson@gmail.com>
 * @copyright GNU Public License v3
 * @package EtuFW
 */

abstract class BasicObject {
	protected $db;             ///< For database instance
	protected $data = array(); ///< Storage of data in the class
	protected $strict;         ///< If the class should be strict or not in the setter
	protected $structure;      ///< Structure of the data
	
	/**
	 * Construction of class...
	 * 
	 * @param &$db Gets a Db class, maybe.
	 * @param $structure Defines the structure of the data in the class
	 * @param $strict bool Defines if the class should be strict on getters/setters or not
	 */
	public function __construct(&$db, Array $structure = Null, $strict = False) {
		$this->initDb($db);
		$this->strict = $strict;
		
		if($structure === Null)
			$this->structure = array();
		else
			$this->structure = $structure;
		
	}
	
	private function initDb(&$db) {
		if(is_object($db)) {
			if(get_class($db) === 'Db') {
				$this->db = $db;
			}
		} else {
			$this->db = new Db;
		}
	}
	
	/**
	 * Defines a custom setter, and typecasts, and checks if it's allowed to set or not
	 * 
	 * @returns false if not allowed
	 * @returns true if succsess
	 */
	public function __set($key, $value) {
		if($this->strict === True && !isset($this->structure[$key]))
			return false;
		
		if(isset($this->structure[$key]))
			settype($value, $this->structure[$key]);
		
		$this->data[$key] = $value;
		
		return true;
	}
	
	/**
	 * Defines a custom getter, to get the data.
	 * 
	 * @returns The data with the key requested
	 */
	public function __get($key) {
		return $this->data[$key];
	}
}
