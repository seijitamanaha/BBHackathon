<?php

/*

http://www.nossosite.com /create.php?json="#####"


name = string
endtime = DD/MM/YYYY
privacy = 	0 - public
			1 - friends
			2 - private
*/

include_once("functions.php");

if (isset($_REQUEST['json'])) {
	echo json_encode(create_task($_REQUEST['json']));
}
?>