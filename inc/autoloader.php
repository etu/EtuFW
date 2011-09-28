<?php

function __autoload($name) {
	$class = 'class'.strtolower(preg_replace('/[A-Z]/', '_$0', $name)).'.php';
	
	require_once($class);
}
