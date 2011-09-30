<?php

/**
 * ModelExample, Very simple example for a model, you got the Db class availble. Use it wisely.
 * 
 * @author Elis Axelsson <elis.axelsson@gmail.com>
 * @copyright GNU Public License v3
 * @package Application
 */

class ModelExample {
	/**
	 * Do not mind the constructor too much... It provides you the Db instance, use it.
	 */
	public function __construct(Db &$db) {
		$this->db = $db;
	}
	
	/**
	 * Example method which you call from the controller.
	 */
	public function sayHello() {
		return "Hello from the example model!";
	}
}
