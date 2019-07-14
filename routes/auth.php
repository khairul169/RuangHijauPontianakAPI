<?php

class AuthRoute {
	
	function load($route) {
		switch ($route->getActionParam()) {
			case 'login':
				$this->login($route);
			
			case 'validate_session':
				$this->validate_session($route);
			
			default:
				break;
		}
	}
	
	function login($route) {
		$username = $route->getData('username');
		$password = $route->getData('password');
		
		if (!$username || !$password)
			return;
		
		if ($username == 'a' && $password == 'a') {
			$route->setResult(0, [
				'sessionId'	=> 'g'
			]);
		}
	}
	
	function validate_session($route) {
		$session = $route->getData('sessionId');
		
		if (!$session)
			return;
		
		if ($session != 'g') {
			return;
		}
		
		$route->setResult(0, [
			'sessionId'	=> 'g'
		]);
	}
}

?>
