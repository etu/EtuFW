<?php
session_start();

// Some static variables defining where stuff is located
define('ROOT_DIR', preg_replace('/\/etufw\/inc$/', '', __DIR__));
define('ETUFW', ROOT_DIR.'/etufw');

// Building array of contents I want in my includepath
$include_path = array(
	get_include_path(),
	ETUFW.'/classes',
	ROOT_DIR.'/libs'
);

// Updating includepath
set_include_path(join(':', $include_path));

require_once('autoloader.php');

$cfg = new Config();
$db = new Db($cfg);
$uri = new UriParser($db, $cfg);

try {
	$controller = new Controller($uri, $db, $cfg);
} catch (Exception $e) {
	header("HTTP/1.0 404 Not Found");
	echo $e."\n";
  }
