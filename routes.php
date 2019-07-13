<?php
define('ROUTES_DIR', __DIR__ . '/routes/');

// routes
include ROUTES_DIR . 'feeds.php';

class Routes {
	var $route;
	
	function __construct($route_name) {
		switch ($route_name) {
			case 'feeds':
				$this->route = new Feeds($this);
				break;
			
			default:
				$this->setResult(-1);
				break;
		}
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

$routeName = isset($_GET['r']) ? trim($_GET['r']) : NULL;
$routes = new Routes($routeName);

?>
