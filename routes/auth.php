<?php

class AuthRoute {
	// vars
	var $route;
	
	function load($route) {
		$this->route = $route;
		
		switch ($route->getActionParam()) {
			case 'login':
				$this->login();
			
			case 'register':
				$this->register();
			
			case 'validate_session':
				$this->validate_session();
			
			default:
				break;
		}
	}
	
	function login() {
		$username = $this->route->getData('username');
		$password = $this->route->getData('password');
		
		if (!$username || !$password)
			return $this->route->setResult(1, "Nama pengguna atau password kosong.");
		
		// escape strings
		$username = $this->route->db->escape_string($username);
		$password = $this->route->db->escape_string($this->route->auth->getPasswordHash($password));
		
		// check user
		$result = $this->route->db->fetch_one("SELECT id FROM users WHERE username='$username' AND password='$password' LIMIT 1;");
		
		if (!$result)
			return $this->route->setResult(2, "Nama pengguna atau password salah.");
		
		// create session
		$sessionId = $this->route->auth->getUserSessionId($result->id);
		
		$this->route->setResult(0, [
			'sessionId'	=> $sessionId
		]);
	}
	
	function register() {
		$fullName = $this->route->getData('fullName');
		$username = $this->route->getData('username');
		$password = $this->route->getData('password');
		
		if (!$fullName || !$username || !$password)
			return $this->route->setResult(1, "Terdapat input yang kosong.");
		
		// check if username is exists
		$result = $this->route->db->query("SELECT id FROM users WHERE username='$username' LIMIT 1;");
		
		if ($result->num_rows)
			return $this->route->setResult(2, "Nama pengguna telah digunakan!");
		
		// escape strings
		$fullName = $this->route->db->escape_string($fullName);
		$username = $this->route->db->escape_string($username);
		$password = $this->route->db->escape_string($this->route->auth->getPasswordHash($password));
		$timestamp = time();
		
		// create user
		$result = $this->route->db->query("INSERT INTO users (username, password, name, registered) VALUES ('$username', '$password', '$fullName', '$timestamp');");
		
		if (!$result)
			return $this->route->setResult(3, "Gagal membuat akun!");
		
		// user id
		$userId = $this->route->db->last_insert_id();
		$sessionId = $this->route->auth->getUserSessionId($userId);
		
		if ($result) {
			$this->route->setResult(0, [
				'sessionId'	=> $sessionId
			]);
		}
	}
	
	function validate_session() {
		// get params
		$sessionId = $this->route->getData('sessionId');
		
		if (!$sessionId)
			return;
		
		// validate session
		$userId = $this->route->auth->getSessionUser($sessionId);
		
		if (!$userId)
			return;
		
		// create session
		$sessionId = $this->route->auth->getUserSessionId($userId);
		
		$this->route->setResult(0, [
			'sessionId'	=> $sessionId
		]);
	}
}

?>
