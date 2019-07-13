<?php

require_once __DIR__ . '/../post_list.php';

class HomeRoute {
	// load route
	function load($route) {
		$paramId = (int) $route->getParam('id', null);
		
		if (!$paramId)
			$route->setResult(-1);
		
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
			$route->setResult(1);
		
		// return post
		$route->setResult(0, array(
			'post'		=> $post
		));
	}
}

?>