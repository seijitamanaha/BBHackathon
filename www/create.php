<?php
include_once("functions.php");

if (isset($_REQUEST['json'])) {
	echo json_encode(create_task(stripslashes($_REQUEST['json'])));
}
?>