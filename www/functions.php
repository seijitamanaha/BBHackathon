<?php

$con_number = 0;
$con = null;
$debug = false;

include_once("functions/error.php");
include_once("functions/user.php");
include_once("functions/task.php");


function dodebug($txt) {
	global $debug;
	if ($debug) {
		echo $txt . "<br/>";
	}
}

function dbconnect() {
	global $con;
	global $con_number;
	if ($con != null)
	{
		$con_number += 1;
		return $con;
	}
	$hostname='bbhackathon.db.8718060.hostedresource.com';
	$username='bbhackathon'; //bbhackathon@72.167.233.37
	$password='EC011bbdb';
	$dbname='bbhackathon';

	$con = mysql_connect($hostname, $username, $password) OR DIE ('Unable to connect to database! Please try again later.');
	$con_number += 1;
	mysql_select_db($dbname);
	return $con;
}

function dbclose() {
	global $con;
	global $con_number;
	if ($con_number == 1)
	{
		mysql_close($con);
		$con = null;
		$con_number--;
	}
	else if ($con_number > 1)
	{
		$con_number--;
	}
	else
	{
		die("Foram fechadas mais conexoes que abertas!");
	}
}


?>