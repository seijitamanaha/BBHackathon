<?php

include_once("functions.php");

if (isset($_REQUEST['json'])) {
	$ret = login(stripslashes($_REQUEST['json']));
	var_dump($ret);
	//echo json_encode($ret);
}
?>