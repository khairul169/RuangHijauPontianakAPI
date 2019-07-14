<?php

class UserAuth {
	function __construct() {
		
	}
	
	function isAuthenticated() {
		return false;
	}
	
	function getAuthToken() {
		foreach (getallheaders() as $key => $val) {
			if (strtolower($key) == 'authorization')
				return substr($val, strpos($val, ' ') + 1);
		}
		return null;
	}
}

?>