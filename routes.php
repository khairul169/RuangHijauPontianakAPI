<?php
define('ROUTES_DIR', __DIR__ . '/routes/');

// routes
include ROUTES_DIR . 'feeds.php';

class Routes {
	var $route;
	
	function __construct($routeParam) {
		// get route name
		$routeName = isset($_GET[$routeParam]) ? trim($_GET[$routeParam]) : NULL;
		
		// create route
		$this->route = $this->getRoute($routeName);
	}
	
	function getRoute($uri) {
		switch ($uri) {
			// feeds
			case 'feeds':
				return new Feeds($this);	
			
			default:
				break;
		}
		
		return NULL;
	}
	
	function loadRoute() {
		if ($this->route)
			// load route
			$this->route->load();
		else
			// route isn't valid
			$this->setResult(-1);
	}
	
	function setResult($returnCode, $args = NULL) {
		$res = array('status' => $returnCode);
		if ($args && is_array($args)) {
			foreach ($args as $k => $v) {
				$res[$k] = $v;
			}
		}
		echo json_encode($res);
		exit();
	}
}

?>
