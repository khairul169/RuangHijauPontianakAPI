<?php

function getPostList($route) {
	$img = $route->getUrlPath('images/tanam_pohon.jpg');
	
	$posts = array();
	$posts[] = array(
		'id'		=> 1,
		'image'		=> $img,
		'name'		=> 'Khairul Hidayat',
		'username'	=> 'khairul169',
		'likes'		=> 12,
		'liked'		=> false
	);

	$posts[] = array(
		'id'		=> 2,
		'image'		=> $img,
		'name'		=> 'Wew Hehe',
		'username'	=> 'lmao',
		'likes'		=> 82,
		'liked'		=> true
	);

	$posts[] = array(
		'id'		=> 3,
		'image'		=> $img,
		'name'		=> 'Hoho hihi',
		'username'	=> 'wewhehe',
		'likes'		=> 0,
		'liked'		=> false
	);
	
	return $posts;
}

?>
