<?php

function getPostList($route) {
	$img = $route->getUrlPath('images/tanam_pohon.jpg');
	
	$posts = array();
	$posts[] = array(
		'id'		=> 1,
		'image'		=> $img,
		'name'		=> 'Khairul Hidayat',
		'username'	=> 'khairul169',
		'date'		=> date('d M Y H.i'),
		'location'	=> 'Siantan Hulu',
		'likes'		=> 12,
		'liked'		=> false,
		'desc'		=> "Hello world! this is some photo description text to describe photo in some words."
	);

	$posts[] = array(
		'id'		=> 2,
		'image'		=> $img,
		'name'		=> 'Wew Hehe',
		'username'	=> 'lmao',
		'date'		=> date('d M Y H.i'),
		'location'	=> 'Siantan Hilir',
		'likes'		=> 82,
		'liked'		=> true,
		'desc'		=> "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur."
	);

	$posts[] = array(
		'id'		=> 3,
		'image'		=> $img,
		'name'		=> 'Hoho hihi',
		'username'	=> 'wewhehe',
		'date'		=> date('d M Y H.i'),
		'location'	=> 'Kota Baru',
		'likes'		=> 0,
		'liked'		=> false,
		'desc'		=> "Test jajaja hehehe.\n\nWEaweaweaw"
	);
	
	return $posts;
}

?>
