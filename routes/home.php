<?php

class HomeRoute {
	// vars
	var $route;
	var $userId;
	
	function load($route) {
		$this->route = $route;
		$this->userId = $route->auth->isAuthenticated();
		
		if (!$this->userId)
			return;
		
		$posts = [];
		$dbRes = $route->db->fetch("SELECT * FROM posts ORDER BY likes DESC LIMIT 5;");
		
		foreach ($dbRes as $row) {
			// get user data
			$userId = $row->user;
			$user = $this->route->db->fetch_one("SELECT * FROM users WHERE id='$userId' LIMIT 1;");
			
			if (!$user)
				continue;
			
			$posts[] = [
				'id'		=> $row->id,
				'image'		=> $route->getImageUrl($row->image),
				'name'		=> $user->name,
				'username'	=> $user->username
			];
		}
		
		// return data
		$route->setResult(0, [
			'penghijauan'	=> $this->getTotalPenghijauan(),
			'unggahan'		=> $posts,
			'kegiatan'		=> null
		]);
	}
	
	function getTotalPenghijauan() {
		$result = $this->route->db->fetch_one("SELECT COUNT(id) AS `total` FROM posts;");
		
		if ($result)
			return $result->total;
		
		return 0;
	}
}

?>
