<?php

class ControllerStart extends BasicController {
	public function __construct(&$uri, &$db) {
		parent::__construct($uri, $db);
	}
	
	public function index() {
		$model = parent::model('example');
		$text = $model->sayHello();
		
		$view = parent::view('start');
		$view->addData('hello', $text);
		$view->render();
	}
}
