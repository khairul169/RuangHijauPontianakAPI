<?php
include __DIR__ . '/database.php';

// setup database
$db = new Database;

// users table
$db->query("CREATE TABLE IF NOT EXISTS users (
	id int(255) NOT NULL AUTO_INCREMENT,
	username varchar(255) NOT NULL,
	password varchar(255) NOT NULL,
	name varchar(255) NOT NULL,
	location varchar(255) NOT NULL,
	registered int(255) NOT NULL,
	PRIMARY KEY (id)
) DEFAULT CHARSET=utf8;");

// posts table
$db->query("CREATE TABLE IF NOT EXISTS posts (
	id int(255) NOT NULL AUTO_INCREMENT,
	user int(255) NOT NULL,
	image varchar(255) NOT NULL,
	description longtext NOT NULL,
	likes int(255) NOT NULL,
	user_likes JSON NOT NULL,
	timestamp int(255) NOT NULL,
	PRIMARY KEY (id)
) DEFAULT CHARSET=utf8;");

// user session table
$db->query("CREATE TABLE IF NOT EXISTS user_session (
	id int(255) NOT NULL AUTO_INCREMENT,
	user int(255) NOT NULL,
	session_id varchar(255) NOT NULL,
	last_update int(255) NOT NULL,
	PRIMARY KEY (id)
) DEFAULT CHARSET=utf8;");

// directories
if (!file_exists('./userimages/')) {
	mkdir('./userimages/', 0777, true);
}

?>