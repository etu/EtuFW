<?php
session_start();

// Updating includepath to contain classes folder and include folder
set_include_path(get_include_path().':../inc:../classes');

require_once('autoloader.php');

$db = new Db();
$uri = new UriParser($db);

try {
	$controller = new Controller($uri, $db);
} catch (Exception $e) {
	header("HTTP/1.0 404 Not Found");
	echo $e."\n";
  }
