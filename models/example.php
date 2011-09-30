<?php

class ModelExample {
	function __construct() {
		$this->db = new Db;
	}
	
	function sayHello() {
		return "Hello from the example model!";
	}
}
