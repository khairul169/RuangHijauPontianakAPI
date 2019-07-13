<?php

class Feeds {
	var $route;
	
	function __construct($route) {
		$this->route = $route;
	}
	
	function load() {
		$test = $this->route->getParam('test', 'hehe');
		$this->route->setResult(0, array(
			'test'	=> $test
		));
	}
}

?>