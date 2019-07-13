<?php

require_once __DIR__ . '/../post_list.php';

class FeedsRoute {
	// load route
	function load($route) {
		$posts = getPostList($route);
		
		$route->setResult(0, array(
			'posts'		=> $posts
		));
	}
}

?>