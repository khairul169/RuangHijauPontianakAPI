<?php

class CommentsRoute {
	// vars
	var $route;
	var $userId;
	
	function load($route) {
		// store route
		$this->route = $route;
		
		// get user authentication
		$this->userId = $route->auth->isAuthenticated();
		
		if (!$this->userId)
			return;
		
		switch ($route->getActionParam()) {
			case 'get':
				$this->get();
			
			case 'add':
				$this->add();
			
			default:
				break;
		}
	}
	
	function get() {
		// params
		$postId = (int) $this->route->getParam('post');
		
		if (!$postId)
			return;
		
		$result = $this->route->db->fetch("SELECT * FROM comments WHERE post_id='$postId' ORDER BY id DESC;");
		$comments = [];
		
		if ($result) {
			foreach ($result as $row) {
				$userId = $row->user;
				$user = $this->route->db->fetch_one("SELECT * FROM users WHERE id='$userId' LIMIT 1;");
				
				if (!$user)
					continue;
				
				$comments[] = [
					'id'		=> $row->id,
					'name'		=> $user->name,
					'date'		=> date('d M Y H.i', $row->timestamp),
					'comment'	=> $row->comment
				];
			}
		}
		
		$this->route->setResult(0, [
			'comments' => $comments
		]);
	}
	
	function add() {
		// params
		$postId = (int) $this->route->getData('post');
		$comment = $this->route->getData('comment', '');
		
		if (!$postId || !$comment)
			return;
		
		// check post id
		$result = $this->route->db->query("SELECT id FROM posts WHERE id='$postId' LIMIT 1;");
		
		if (!$result || !$result->num_rows)
			return;
		
		$user = $this->userId;
		$comment = $this->route->db->escape_string(trim($comment));
		$time = time();
		
		$result = $this->route->db->query("INSERT INTO comments (type, post_id, user, comment, timestamp) 
		VALUES ('0', '$postId', '$user', '$comment', '$time');");
		
		if ($result)
			$this->route->setResult(0);
	}
}

?>
