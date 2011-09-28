<?php

/**
 * BasicObject class, gives custom getters, setters and Db init stuff for all classes
 *
 * @author Elis Axelsson <elis.axelsson@gmail.com>
 * @copyright GNU Public License v3
 */

class BasicObject {
	protected $db;             ///< For database instance
	protected $data = array(); ///< Storage of data in the class
	protected $strict;         ///< If the class should be strict or not in the setter
	protected $structure;      ///< Structure of the data
	
	/**
	 * Construction of class...
	 * 
	 * @param &$db Gets a Db class, maybe.
	 * @param $structure array Defines the structure of the data in the class
	 * @param $strict bool Defines if the class should be strict on getters/setters or not
	 */
	public function __construct(&$db, $structure = False, $strict = False) {
		$this->initDb($db);
		$this->strict = $strict;
		
		if($structure != False) {
			$this->structure = $structure;
		} else {
			$this->structure = array();
		}
	}
	
	private function initDb(&$db) {
		if($db === False)
			$this->db = new Db;
		else
			$this->db = $db;
	}
	
	/**
	 * Defines a custom setter, and typecasts, and checks if it's allowed to set or not
	 * 
	 * @returns false if not allowed
	 * @returns true if succsess
	 */
	public function __set($key, $value) {
		if($this->strict === True) {
			if(isset($this->structure[$key])) {
				settype($value, $this->structure[$key]);
				$this->data[$key] = $value;
			} else {
				return false;
			}
		} else {
			if(isset($this->structure[$key]))
				settype($value, $this->structure[$key]);
			
			$this->data[$key] = $value;
		}
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
