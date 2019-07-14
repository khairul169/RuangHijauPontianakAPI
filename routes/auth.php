<?php

require_once __DIR__ . '/../auth.php';

class AuthRoute {
	var $auth;
	
	function __construct() {
		$this->auth = new UserAuth;
	}
	
	function load($route) {
		switch ($route->getActionParam()) {
			case 'login':
				$this->login($route);
			
			case 'register':
				$this->register($route);
			
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
		
		// escape strings
		$username = $route->db->escape_string($username);
		$password = $route->db->escape_string($this->auth->getPasswordHash($password));
		
		// check user
		$result = $route->db->fetch_one("SELECT id FROM users WHERE username='$username' AND password='$password' LIMIT 1;");
		
		if (!$result)
			return;
		
		// create session
		$sessionId = $this->auth->getUserSessionId($result['id']);
		
		$route->setResult(0, [
			'sessionId'	=> $sessionId
		]);
	}
	
	function register($route) {
		$fullName = $route->getData('fullName');
		$username = $route->getData('username');
		$password = $route->getData('password');
		
		if (!$fullName || !$username || !$password)
			return;
		
		// check if username is exists
		$result = $route->db->query("SELECT id FROM users WHERE username='$username' LIMIT 1;");
		
		if ($result->num_rows)
			return;
		
		// escape strings
		$fullName = $route->db->escape_string($fullName);
		$username = $route->db->escape_string($username);
		$password = $route->db->escape_string($this->auth->getPasswordHash($password));
		$timestamp = time();
		
		// create user
		$result = $route->db->query("INSERT INTO users (username, password, name, registered) VALUES ('$username', '$password', '$fullName', '$timestamp');");
		
		if (!$result)
			return;
		
		// user id
		$userId = $route->db->last_insert_id();
		$sessionId = $this->auth->getUserSessionId($userId);
		
		if ($result) {
			$route->setResult(0, [
				'sessionId'	=> $sessionId
			]);
		}
	}
	
	function validate_session($route) {
		// get params
		$sessionId = $route->getData('sessionId');
		
		if (!$sessionId)
			return;
		
		// validate session
		$userId = $this->auth->getSessionUser($sessionId);
		
		if (!$userId)
			return;
		
		// create session
		$sessionId = $this->auth->getUserSessionId($userId);
		
		$route->setResult(0, [
			'sessionId'	=> $sessionId
		]);
	}
}

?>
