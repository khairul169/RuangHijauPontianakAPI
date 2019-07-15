<?php

class HomeRoute {
	var $route;
	
	function load($route) {
		$this->route = $route;
		
		$posts = [];
		$dbRes = $route->db->fetch("SELECT * FROM posts ORDER BY likes DESC LIMIT 5;");
		
		foreach ($dbRes as $row) {
			$posts[] = [
				'id'		=> $row->id,
				'image'		=> $route->getUrlPath('userimages/' . $row->image),
				'name'		=> 'Test',
				'username'	=> 'test'
			];
		}
		
		// return data
		$route->setResult(0, [
			'penghijauan'	=> $this->getTotalPenghijauan(),
			'unggahan'		=> $posts,
			'kegiatan'		=> null
		]);
	}
	
	function getTotalPenghijauan() {
		$result = $this->route->db->fetch_one("SELECT COUNT(id) AS `total` FROM posts;");
		
		if ($result)
			return $result->total;
		
		return 0;
	}
}

?>
