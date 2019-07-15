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
		$dbRes = $route->db->fetch("SELECT * FROM posts ORDER BY id DESC");
		
		foreach ($dbRes as $row) {
			$posts[] = [
				'id'		=> $row->id,
				'image'		=> $route->getUrlPath('userimages/' . $row->image),
				'name'		=> 'Test',
				'username'	=> 'test',
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
