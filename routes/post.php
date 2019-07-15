<?php

class PostRoute {
	
	function load($route) {
		switch ($route->getActionParam()) {
			case 'get_post':
				$this->get_post($route);
			
			case 'create':
				$this->create($route);
			
			default:
				break;
		}
	}
	
	function get_post($route) {
		// params
		$paramId = (int) $route->getParam('id', null);
		
		if (!$paramId)
			return;
		
		// escape string
		$paramId = $route->db->escape_string($paramId);
		
		// get post by id
		$row = $route->db->fetch_one("SELECT * FROM posts WHERE id='$paramId' LIMIT 1;");
		
		// post isn't found
		if (!$row)
			return;
		
		$post = [
			'id'		=> $row->id,
			'image'		=> $route->getUrlPath('userimages/' . $row->image),
			'name'		=> 'Test',
			'desc'		=> $row->description,
			'location'	=> 'Siantan',
			'date'		=> date('d M Y H.i', $row->timestamp),
			'likes'		=> $row->likes,
			'liked'		=> $this->isPostLiked($route, $row->id)
		];
		
		$route->setResult(0, [
			'post' => $post
		]);
	}
	
	function create($route) {
		// params
		$imageData = $route->getData('image');
		$desc = $route->getData('desc', '');
		
		if (!$imageData)
			return;
		
		$imageName = md5(rand().time()) . '.jpg';
		$imageRes = $this->saveImage($imageData, $imageName);
		
		if (!$imageRes)
			return;
		
		$user = 1;
		$imageName = $route->db->escape_string($imageName);
		$desc = $route->db->escape_string($desc);
		$timestamp = time();
		
		$dbRes = $route->db->query("INSERT INTO posts (
		user, image, description, timestamp
		) VALUES (
		'$user', '$imageName', '$desc', '$timestamp'
		);");
		
		if ($dbRes)
			$route->setResult(0);
	}
	
	function saveImage($data, $fileName) {
		$image = base64_decode($data);
		$image = imagecreatefromstring($image);
		
		$path = __DIR__ . '/../userimages/' . $fileName;
		$imageRes = imagejpeg($image, $path, 80);
		imagedestroy($image);
		
		return $imageRes;
	}
	
	function isPostLiked($route, $postId) {
		$result = $route->db->query("SELECT * FROM posts WHERE id='$postId' AND JSON_CONTAINS(user_likes, '1');");
		return $result && ($result->num_rows > 0);
	}
}

?>
