<?php
define('ROUTES_DIR', __DIR__ . '/routes/');

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/database.php';
require_once __DIR__ . '/auth.php';

// routes
include ROUTES_DIR . 'auth.php';
include ROUTES_DIR . 'home.php';
include ROUTES_DIR . 'feeds.php';
include ROUTES_DIR . 'post.php';
include ROUTES_DIR . 'profile.php';

class Routes {
	// vars
	var $route;
	var $db;
	var $auth;
	
	function __construct($routeParam) {
		// initialize database
		$this->db = new Database;
		
		// user authentication
		$this->auth = new UserAuth;
		
		// get route name
		$routeName = isset($_GET[$routeParam]) ? trim($_GET[$routeParam]) : NULL;
		
		// create route
		$this->route = $this->getRoute($routeName);
	}
	
	function getRoute($uri) {
		// routes
		switch ($uri) {
			case 'auth':
				return new AuthRoute;
			case 'home':
				return new HomeRoute;
			case 'feeds':
				return new FeedsRoute;
			case 'post':
				return new PostRoute;
			case 'profile':
				return new ProfileRoute;
			
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
	function getData($name, $defval = NULL) {
		return isset($_POST[$name]) ? $_POST[$name] : $defval;
	}
	
	function getUrlPath($path = '') {
		// base url
		$url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
		
		// request uri
		$requestUrl = $_SERVER['REQUEST_URI'];
		$requestUrl = substr($requestUrl, 0, strrpos($requestUrl, "/"));
		
		return $url . $requestUrl . $path;
	}
	
	function getImageUrl($fname) {
		global $config;
		return $this->getUrlPath($config['path']['user_img'] . $fname);
	}
	
	function getImagePath($fname) {
		global $config;
		return __DIR__ . $config['path']['user_img'] . $fname;
	}
	
	function getActionParam() {
		return $this->getParam('action', null);
	}
}

?>
