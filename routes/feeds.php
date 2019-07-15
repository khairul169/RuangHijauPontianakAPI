<?php

class FeedsRoute {
	// vars
	var $route;
	var $userId;
	
	function load($route) {
		$this->route = $route;
		$this->userId = $route->auth->isAuthenticated();
		
		if (!$this->userId)
			return;
		
		$posts = [];
		$rows = $route->db->fetch("SELECT * FROM posts ORDER BY id DESC");
		
		if (!$rows)
			$route->setResult(0, array(
				'posts'		=> []
			));
		
		foreach ($rows as $row) {
			// get user data
			$userId = $row->user;
			$user = $this->route->db->fetch_one("SELECT * FROM users WHERE id='$userId' LIMIT 1;");
			
			if (!$user)
				continue;
			
			$posts[] = [
				'id'		=> $row->id,
				'image'		=> $route->getImageUrl($row->image),
				'name'		=> $user->name,
				'username'	=> $user->username,
				'likes'		=> $row->likes,
				'liked'		=> $this->isPostLiked($row->id)
			];
		}
		
		$route->setResult(0, array(
			'posts'		=> $posts
		));
	}
	
	function isPostLiked($postId) {
		$userId = $this->userId;
		$result = $this->route->db->query("SELECT * FROM posts WHERE id='$postId' AND JSON_CONTAINS(user_likes, '$userId');");
		return $result && $result->num_rows;
	}
}

?>
