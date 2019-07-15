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
		
		// return data
		$route->setResult(0, [
			'penghijauan'	=> $this->getTotalPenghijauan(),
			'unggahan'		=> $this->getHighlightedPost(),
			'kegiatan'		=> $this->getUpcomingEvent()
		]);
	}
	
	function getTotalPenghijauan() {
		$result = $this->route->db->fetch_one("SELECT COUNT(id) AS `total` FROM posts;");
		
		if ($result)
			return $result->total;
		
		return 0;
	}
	
	function getHighlightedPost() {
		$result = $this->route->db->fetch("SELECT * FROM posts ORDER BY likes DESC LIMIT 5;");
		
		if (!$result)
			return null;
		
		$posts = [];
		
		foreach ($result as $row) {
			// get user data
			$userId = $row->user;
			$user = $this->route->db->fetch_one("SELECT * FROM users WHERE id='$userId' LIMIT 1;");
			
			if (!$user)
				continue;
			
			$posts[] = [
				'id'		=> $row->id,
				'image'		=> $this->route->getImageUrl($row->image),
				'name'		=> $user->name,
				'username'	=> $user->username
			];
		}
		
		return $posts;
	}
	
	function getUpcomingEvent() {
		$time = time();
		$result = $this->route->db->fetch_one("SELECT * FROM events WHERE timestamp > '$time' ORDER BY timestamp ASC LIMIT 1;");
		
		if (!$result)
			return null;
		
		return [
			'id'		=> $result->id,
			'image'		=> $this->route->getImageUrl($result->image),
			'name'		=> $result->name,
			'time'		=> $this->getTimeString($result->timestamp)
		];
	}
	
	function getTimeString($time) {
		$time = $time - time();
		
		if ($time >= 0) {
			$days = (int) ceil($time/(60*60*24));
			$hours = (int) ceil($time/(60*60));
			
			if ($days > 1)
				$time = $days . ' HARI LAGI';
			else
				$time = $hours . ' JAM LAGI';
		} elseif ($time >= -60*60*24) {
			$time = 'Sedang Berlangsung';
		} else {
			$time = 'Selesai';
		}
		
		return $time;
	}
}

?>
