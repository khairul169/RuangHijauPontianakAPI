<?php

$obj = [
	1,
	2,
	4
];

echo json_encode($obj);
echo "<br />";

if (in_array(2, $obj))
	array_splice($obj, array_search(2, $obj), 1);

echo json_encode($obj);
echo "<br />";

?>