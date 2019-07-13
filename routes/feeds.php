<?php

class Feeds {
	var $route;
	
	function __construct($route) {
		$this->route = $route;
	}
	
	function load() {
		$test = $this->route->getParam('test', 'hehe');
		
		$img = $this->route->getUrlPath('images/tanam_pohon.jpg');
		
		$posts = array();
		$posts[] = array(
			'id'		=> 1,
			'image'		=> $img,
			'name'		=> 'Khairul Hidayat',
			'username'	=> 'khairul169',
			'like'		=> 12,
			'liked'		=> false
		);
		
		$posts[] = array(
			'id'		=> 2,
			'image'		=> $img,
			'name'		=> 'Wew Hehe',
			'username'	=> 'lmao',
			'like'		=> 82,
			'liked'		=> true
		);
		
		$posts[] = array(
			'id'		=> 3,
			'image'		=> $img,
			'name'		=> 'Hoho hihi',
			'username'	=> 'wewhehe',
			'like'		=> 0,
			'liked'		=> false
		);
		
		$this->route->setResult(0, array(
			'test'		=> $test,
			'posts'		=> $posts
		));
	}
}

?>