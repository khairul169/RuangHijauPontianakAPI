<?php

require_once __DIR__ . '/config.php';

class Database {
	var $db;
	
	function __construct() {
		global $config;
		
		if ($config && isset($config['db'])) {
			$this->connect($config['db']);
		}
	}
	
	function __destruct() {
		if ($this->db)
			$this->db->close();
	}
	
	function connect($config) {
		if ($this->db)
			return;
		
		$this->db = new mysqli($config['host'], $config['user'], $config['pass'], $config['db']);
		
		if ($this->db->connect_error) {
			echo $this->db->connect_error;
			exit;
		}
	}
	
	function query($sql) {
		if (!$this->db)
			return false;
		
		$result = $this->db->query($sql);
		
		if (!$result) {
			echo $this->db->error;
			exit;
		}
		
		return $result;
	}
	
	function fetch($sql) {
		if (!$this->db)
			return false;
		
		$result = $this->query($sql);
		$rows = [];
		
		if ($result) {
			while ($row = $result->fetch_assoc()) {
				$rows[] = $row;
			}
		}
		
		return $rows;
	}
	
	function fetch_one($sql) {
		if (!$this->db)
			return false;
		
		$result = $this->query($sql);
		
		if ($result && $result->num_rows)
			return $result->fetch_assoc();
		
		return false;
	}
	
	function last_insert_id() {
		if (!$this->db)
			return false;
		
		return $this->db->insert_id;
	}
	
	function escape_string($str) {
		if ($this->db)
			return $this->db->real_escape_string($str);
		return false;
	}
}

?>