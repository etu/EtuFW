<?php

/**
 * ControllerStart is one of the simplest controller you can make, this is just a example.
 * 
 * @author Elis Axelsson <elis.axelsson@gmail.com>
 * @copyright GNU Public License v3
 * @package Application
 */

class ControllerStart extends BasicController {
	/**
	 * Use this constructor when you build your own class, you can extend it if you want,
	 * but this structure is required.
	 * 
	 * @param $uri UriParser instance
	 * @param $db  Db instance
	 */
	public function __construct(UriParser &$uri, Db &$db) {
		parent::__construct($uri, $db);
	}
	
	/**
	 * This is the action "index" as in, /index.php/start/index in this case.
	 */
	public function index() {
		$model = parent::model('example');
		$text = $model->sayHello();
		
		$view = parent::view('start');
		$view->addData('hello', $text);
		$view->render();
	}
}
