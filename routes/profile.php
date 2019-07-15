<?php

class ProfileRoute {
	// vars
	var $userId;
	
	function load($route) {
		$this->userId = $route->auth->isAuthenticated();
		
		if (!$this->userId)
			return;
		
		switch ($route->getActionParam()) {
			case 'view':
				$this->view($route);
			
			default:
				break;
		}
	}
	
	function view($route) {
		// params
		$paramId = (int) $route->getParam('id', null);
		
		if (!$paramId)
			$paramId = $this->userId;
		
		// get user data
		$user = $route->db->fetch_one("SELECT * FROM users WHERE id='$paramId' LIMIT 1;");
		
		if (!$user)
			return;
		
		// get user data
		$userPosts = $route->db->fetch("SELECT * FROM posts WHERE user='$paramId';");
		$posts = [];
		$likes = 0;
		
		if ($userPosts) {
			foreach ($userPosts as $row) {
				$likes = $likes + (int) $row->likes;
				
				$posts[] = [
					'id'	=> $row->id,
					'image' => $route->getUrlPath('userimages/' . $row->image)
				];
			}
		}
		
		$profile = [
			'name'		=> $user->name,
			'username'	=> $user->username,
			'location'	=> $user->location
		];
		
		$route->setResult(0, [
			'profile'	=> $profile,
			'posts'		=> $posts,
			'post_count'	=> count($posts),
			'likes'		=> $likes
		]);
	}
}

?>
