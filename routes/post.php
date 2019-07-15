<?php

class PostRoute {
	// vars
	var $route;
	var $userId;
	
	function load($route) {
		$this->route = $route;
		$this->userId = $route->auth->isAuthenticated();
		
		if (!$this->userId)
			return;
		
		switch ($route->getActionParam()) {
			case 'get_post':
				$this->get_post();
			
			case 'create':
				$this->create();
			
			case 'like':
				$this->like();
			
			default:
				break;
		}
	}
	
	function get_post() {
		// params
		$paramId = (int) $this->route->getParam('id', null);
		
		if (!$paramId)
			return;
		
		// get post by id
		$row = $this->route->db->fetch_one("SELECT * FROM posts WHERE id='$paramId' LIMIT 1;");
		
		// post isn't found
		if (!$row)
			return;
		
		// get user data
		$userId = $row->user;
		$user = $this->route->db->fetch_one("SELECT * FROM users WHERE id='$userId' LIMIT 1;");
		
		if (!$user)
			return;
		
		$post = [
			'id'		=> $row->id,
			'image'		=> $this->route->getUrlPath('userimages/' . $row->image),
			'name'		=> $user->name,
			'desc'		=> $row->description,
			'location'	=> $user->location,
			'date'		=> date('d M Y H.i', $row->timestamp),
			'likes'		=> $row->likes,
			'liked'		=> $this->isPostLiked($row->id)
		];
		
		$this->route->setResult(0, [
			'post' => $post
		]);
	}
	
	function create() {
		// params
		$imageData = $this->route->getData('image');
		$desc = $this->route->getData('desc', '');
		
		if (!$imageData)
			return;
		
		$imageName = md5(rand().time()) . '.jpg';
		$imageRes = $this->saveImage($imageData, $imageName);
		
		if (!$imageRes)
			return;
		
		$user = $this->userId;
		$imageName = $this->route->db->escape_string($imageName);
		$desc = trim($desc);
		$desc = $this->route->db->escape_string($desc);
		$timestamp = time();
		
		$dbRes = $this->route->db->query("INSERT INTO posts (user, image, description, timestamp) 
		VALUES ('$user', '$imageName', '$desc', '$timestamp');");
		
		if ($dbRes)
			$this->route->setResult(0);
	}
	
	function like() {
		// params
		$postId = (int) $this->route->getParam('id', null);
		
		if (!$postId)
			return;
		
		// escape string
		$postId = $this->route->db->escape_string($postId);
		
		// get post by id
		$row = $this->route->db->fetch_one("SELECT * FROM posts WHERE id='$postId' LIMIT 1;");
		
		// post isn't found
		if (!$row)
			return;
		
		// likes data
		$likes_data = isset($row->user_likes) ? json_decode($row->user_likes) : NULL;
		$liked = false;
		
		if ($likes_data) {
			if (in_array($this->userId, $likes_data)) {
				// remove like
				array_splice($likes_data, array_search($this->userId, $likes_data), 1);
			} else {
				// add like
				$likes_data[] = (int) $this->userId;
				$liked = true;
			}
		} else {
			// add like
			$likes_data = [];
			$likes_data[] = (int) $this->userId;
			$liked = true;
		}
		
		$likes = count($likes_data);
		$likes_data = $this->route->db->escape_string(json_encode($likes_data));
		
		// update post likes
		$result = $this->route->db->query("UPDATE posts SET likes='$likes', user_likes='$likes_data' WHERE id='$postId' LIMIT 1;");
		
		if ($result) {
			$this->route->setResult(0, [
				'likes' => $likes,
				'liked'	=> $liked
			]);
		}
	}
	
	function saveImage($data, $fileName) {
		$image = base64_decode($data);
		$image = imagecreatefromstring($image);
		
		$path = __DIR__ . '/../userimages/' . $fileName;
		$imageRes = imagejpeg($image, $path, 80);
		imagedestroy($image);
		
		return $imageRes;
	}
	
	function isPostLiked($postId) {
		$userId = $this->userId;
		$result = $this->route->db->query("SELECT * FROM posts WHERE id='$postId' AND JSON_CONTAINS(user_likes, '$userId');");
		return $result && $result->num_rows;
	}
}

?>
