<?php
session_start();

// Updating includepath to contain classes folder and include folder
set_include_path(get_include_path().':../inc:../classes');

require_once('autoloader.php');

$db = new Db();
$uri = new UriParser($db);

$controller = new Controller($uri);
