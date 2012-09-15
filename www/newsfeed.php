<?php

header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');

$time = date('r');
echo "data: The server time is: {$time}\n\n";
flush();

/*header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');

//while (!connection_aborted()) {
	$t = time() + 1000;
	$t = $t - ($t % 1000);
	time_sleep_until($t);
	echo "data: The server time is: $t\n\n";
	flush();
//*/}

?>