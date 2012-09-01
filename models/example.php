<?php

/**
 * ModelExample, Very simple example for a model, you got the Orm class availble. Use it wisely.
 * 
 * @author Elis Axelsson <elis.axelsson@gmail.com>
 * @copyright GNU Public License v3
 * @package Application
 */

class ModelExample {
	/**
	 * Do not mind the constructor too much... It provides you the Orm instance, use it.
	 */
	public function __construct(Orm &$orm) {
		$this->orm = $orm;
	}
	
	/**
	 * Example method which you call from the controller.
	 */
	public function sayHello() {
		return "Hello from the example model!";
	}
}
