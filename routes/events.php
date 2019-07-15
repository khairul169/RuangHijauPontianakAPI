<?php

class EventsRoute {
	// vars
	var $route;
	var $userId;
	
	function load($route) {
		$this->route = $route;
		$this->userId = $route->auth->isAuthenticated();
		
		if (!$this->userId)
			return;
		
		switch ($route->getActionParam()) {
			case 'view':
				$this->view();
			
			default:
				$this->event_list();
				break;
		}
	}
	
	function event_list() {
		// fetch events
		$rows = $this->route->db->fetch("SELECT * FROM events ORDER BY id DESC");
		
		if (!$rows)
			return;
		
		$events = [];
		
		foreach ($rows as $row) {
			// get event handler
			$handler = $row->handler;
			$user = $this->route->db->fetch_one("SELECT * FROM users WHERE id='$handler' LIMIT 1;");
			
			if (!$user)
				continue;
			
			$events[] = [
				'id'		=> $row->id,
				'image'		=> $this->route->getImageUrl($row->image),
				'name'		=> $row->name,
				'time'		=> $this->getTimeString($row->timestamp)
			];
		}
		
		$this->route->setResult(0, array(
			'events'		=> $events
		));
	}
	
	function view() {
		// params
		$paramId = (int) $this->route->getParam('id', null);
		
		if (!$paramId)
			return;
		
		// get post by id
		$row = $this->route->db->fetch_one("SELECT * FROM events WHERE id='$paramId' LIMIT 1;");
		
		// event isn't found
		if (!$row)
			return;
		
		// get user data
		$userId = $row->handler;
		$handler = $this->route->db->fetch_one("SELECT * FROM users WHERE id='$userId' LIMIT 1;");
		
		if (!$handler)
			return;
		
		$event = [
			'id'		=> $row->id,
			'handler'	=> $row->handler,
			'handlerName'	=> $handler->name,
			'image'		=> $this->route->getImageUrl($row->image),
			'name'		=> $row->name,
			'desc'		=> $row->description,
			'time'		=> date('d M Y H.i', $row->timestamp)
		];
		
		$this->route->setResult(0, [
			'event' => $event
		]);
	}
	
	function create() {
		// params
		$imageData = $this->route->getData('image');
		$name = $this->route->getData('name', '');
		$desc = $this->route->getData('desc', '');
		
		if (!$imageData || !$name)
			return;
		
		$imageName = md5(rand().time()) . '.jpg';
		$imageRes = $this->saveImage($imageData, $this->route->getImagePath($imageName));
		
		if (!$imageRes)
			return;
		
		$user = $this->userId;
		$name = $this->route->db->escape_string(trim($name));
		$desc = $this->route->db->escape_string(trim($desc));
		$timestamp = time() + (60 * 60 * 24 * 2);
		
		$result = $this->route->db->query("INSERT INTO events (handler, image, name, description, timestamp) 
		VALUES ('$user', '$imageName', '$name', '$desc', '$timestamp');");
		
		if ($result)
			$this->route->setResult(0);
	}
	
	function saveImage($data, $path) {
		// decode image
		$image = base64_decode($data);
		
		// save image
		$image = imagecreatefromstring($image);
		$imageRes = imagejpeg($image, $path, 80);
		imagedestroy($image);
		
		return $imageRes;
	}
	
	function getTimeString($time) {
		$time = $time - time();
		
		if ($time >= 0) {
			$days = (int) ceil($time/(60*60*24));
			$hours = (int) ceil($time/(60*60));
			
			if ($days > 1)
				$time = $days . ' HARI LAGI';
			else
				$time = $hours . ' JAM LAGI';
		} elseif ($time >= -60*60*24) {
			$time = 'Sedang Berlangsung';
		} else {
			$time = 'Selesai';
		}
		
		return $time;
	}
}

?>
