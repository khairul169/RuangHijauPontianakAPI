<?php

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/database.php';

class UserAuth {
	var $db;
	
	function __construct() {
		// initialize database
		$this->db = new Database;
	}
	
	function isAuthenticated() {
		if (!$this->db)
			return false;
		
		// get authorization from header
		$sessionId = $this->getAuthToken();
		
		if (!$sessionId)
			return false;
		
		return $this->getSessionUser($sessionId);
	}
	
	function getSessionUser($sessionId) {
		// escape string
		$sessionId = $this->db->escape_string($sessionId);
		
		// get session from database
		$result = $this->db->fetch_one("SELECT * FROM user_session WHERE session_id='$sessionId' LIMIT 1;");
		
		if ($result)
			return $result->id;
		
		return false;
	}
	
	function getPasswordHash($password) {
		global $config;
		
		if (isset($config['hash']))
			$password = md5($config['hash']['prefix'] . $password . $config['hash']['salt']);
		
		return $password;
	}
	
	function getUserSessionId($userId) {
		if (!$this->db || !$userId)
			return false;
		
		$timestamp = time();
		$sessionId = md5($timestamp.$userId);
		
		$result = $this->db->query("SELECT id FROM user_session WHERE user='$userId' LIMIT 1;");
		
		if ($result->num_rows) {
			// update session
			$result = $this->db->query("UPDATE user_session SET session_id='$sessionId', last_update='$timestamp' WHERE user='$userId' LIMIT 1;");
		} else {
			// create new session
			$result = $this->db->query("INSERT INTO user_session (user, session_id, last_update) VALUES ('$userId', '$sessionId', '$timestamp');");
		}
		
		if ($result)
			return $sessionId;
		
		return false;
	}
	
	function getAuthToken() {
		$headers = getallheaders();
		
		foreach ($headers as $key => $val) {
			if (strtolower($key) == 'authorization')
				return substr($val, strpos($val, ' ') + 1);
		}
		
		return null;
	}
}

?>