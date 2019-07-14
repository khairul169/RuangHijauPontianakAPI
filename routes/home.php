<?php

require_once __DIR__ . '/../post_list.php';

class HomeRoute {
	
	function load($route) {
		$posts = getPostList($route);
		
		// return data
		$route->setResult(0, [
			'penghijauan'	=> 112,
			'unggahan'		=> $posts,
			'kegiatan'		=> null
		]);
	}
}

?>
