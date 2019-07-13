<?php
define('ROUTES_DIR', __DIR__ . '/routes/');

// routes
include ROUTES_DIR . 'home.php';
include ROUTES_DIR . 'feeds.php';
include ROUTES_DIR . 'post.php';

class Routes {
	var $route;
	
	function __construct($routeParam) {
		// get route name
		$routeName = isset($_GET[$routeParam]) ? trim($_GET[$routeParam]) : NULL;
		
		// create route
		$this->route = $this->getRoute($routeName);
	}
	
	function getRoute($uri) {
		// routes
		switch ($uri) {
			case 'home':
				return new HomeRoute;
			case 'feeds':
				return new FeedsRoute;
			case 'post':
				return new PostRoute;
			
			default:
				break;
		}
		
		return NULL;
	}
	
	function loadRoute() {
		// load route
		if ($this->route)
			$this->route->load($this);
		
		// return invalid result
		$this->setResult(-1);
	}
	
	function setResult($returnCode, $args = NULL) {
		// set header
		header('Content-Type: application/json');
		
		// set result status
		$res = array('status' => $returnCode);
		
		// push args
		if ($args && is_array($args)) {
			foreach ($args as $k => $v) {
				$res[$k] = $v;
			}
		}
		
		// echo result
		echo json_encode($res);
		exit();
	}
	
	// http get param
	function getParam($name, $defval = NULL) {
		return isset($_GET[$name]) ? trim($_GET[$name]) : $defval;
	}
	
	// http post param
	function getData($name) {
		return isset($_POST[$name]) ? $_POST[$name] : NULL;
	}
	
	function getUrlPath($path = '') {
		// base url
		$url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
		
		// request uri
		$requestUrl = $_SERVER['REQUEST_URI'];
		$requestUrl = substr($requestUrl, 0, strrpos($requestUrl, "/") + 1);
		
		return $url . $requestUrl . $path;
	}
	
	function getActionParam() {
		return $this->getParam('action', null);
	}
}

?>
