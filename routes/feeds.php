<?php

class Feeds {
	var $route;
	
	function __construct($route) {
		$this->route = $route;
		
		$route->setResult(0, array('test' => 1));
	}
}

?>