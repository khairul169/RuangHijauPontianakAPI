<?php

require_once __DIR__ . '/../post_list.php';

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
		$paramId = (int) $route->getParam('id', null);
		
		if (!$paramId)
			return;
		
		$posts = getPostList($route);
		$post = null;
		
		// search post
		foreach ($posts as $key => $row) {
			if ($row['id'] == $paramId) {
				$post = $row;
				break;
			}
		}
		
		// post not found
		if (!$post)
			return;
		
		// return post
		$route->setResult(0, array(
			'post'		=> $post
		));
	}
	
	function create($route) {
		$image = $route->getData('image');
		$desc = $route->getData('desc');
		
		if (!$image || $desc == NULL)
			return;
		
		$imageName = md5(rand().time()) . '.jpg';
		$imageRes = $this->saveImage($image, $imageName);
		
		if ($imageRes)
			$route->setResult(0);
	}
	
	function saveImage($data, $fileName) {
		$image = base64_decode($data);
		$image = imagecreatefromstring($image);
		
		$path = __DIR__ . '/../images/' . $fileName;
		$imageRes = imagejpeg($image, $path, 80);
		imagedestroy($image);
		
		return $imageRes;
	}
}

?>
