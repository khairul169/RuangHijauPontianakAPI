<?php

class FeedsRoute {
	// load route
	function load($route) {
		$posts = [];
		$dbRes = $route->db->fetch("SELECT * FROM posts ORDER BY id DESC");
		
		foreach ($dbRes as $row) {
			$posts[] = [
				'id'		=> $row->id,
				'image'		=> $route->getUrlPath('userimages/' . $row->image),
				'name'		=> 'Test',
				'username'	=> 'test',
				'likes'		=> $row->likes,
				'liked'		=> $this->isPostLiked($route, $row->id)
			];
		}
		
		$route->setResult(0, array(
			'posts'		=> $posts
		));
	}
	
	function isPostLiked($route, $postId) {
		$result = $route->db->query("SELECT * FROM posts WHERE id='$postId' AND JSON_CONTAINS(user_likes, '1');");
		
		if ($result && $result->num_rows)
			return true;
		
		return false;
	}
}

?>
