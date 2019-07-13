<?php
header('Content-Type: application/json');

include __DIR__ . '/routes.php';

$routes = new Routes('r');
$routes->loadRoute();

?>