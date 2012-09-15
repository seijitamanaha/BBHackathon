<?php

$con_number = 0;
$con = null;
$debug = false;

/*

** ERROR IDs **
1 - taskname not defined

*/

final ERROR_NO_TASKNAME = 1;

function dodebug($txt) {
	global $debug;
	if ($debug) {
		echo $txt . "<br/>";
	}
}

function dbconnect()
{
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

function dbclose()
{
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

function getErrorName($error_id) {
	$str = "Erro $error_id : ";
	switch ($error_id) {
		case ERROR_NO_TASKNAME:
			$str .= "Nome da task não definido";
			break;
		default:
			$str .= "Unknown";
			break;
	}
	return $str;
}

function create_task($json) {
	$jary = json_decode($json, true);
	$ret = array();
	
	if (!isset($jary['taskName'])) {
		$ret['error'] = array('id'=>ERROR_NO_TASKNAME, 'msg'=>getErrorName(ERROR_NO_TASKNAME));
		return json_encode($ret);
	}
	
	$name = $jary['taskName'];
	$deadline = isset($jary['taskDeadline']) ? $jary['taskDeadline'] : "NULL";
	$complete = isset($jary['taskComplete']) ? $jary['taskComplete'] : "0";
	$type = isset($jary['taskType']) ? $jary['taskType'] : "0";
	$group = isset($jary['taskGroup']) ? $jary['taskGroup'] : "0";
	$privacy = isset($jary['taskPrivacy']) ? $jary['taskPrivacy'] : "0";
	$owner = 0; /* TODO PEGAR ID DO USUARIO LOGADO */
	$creation = gmdate("T-m-d H:i:s");
	
	dbconnect();
	$query = "INSERT INTO  `bbhackathon`.`task` (
			`id`, `name`, `deadline`, `kids`, `parents`, `type`, `attr`, `privacy`, `owner`, `creation`
			) VALUES (
			NULL, '$jary[taskName]', '$identifier', '', '[1]$folder', '$typestr', '$attrstr', '', '$owner', '$creation');";
	dodebug("DEBUG: QUERY: $query");
	$result = mysql_query($query, $con);
	if ($result) {
		$file_id = mysql_insert_id($con);
		//dodebug("INSERTION SUCCEDED! FID = $file_id");
		$str = push_str_array($finfo['kids'], $file_id);
		$query = "UPDATE `bbhackathon`.`file` SET `file`.`kids` = '$str' WHERE `file`.`id` = $folder LIMIT 1 ;";
		$result = mysql_query($query, $con);
		if (!$result) {
			dodebug("ERROR: KIDS UPDATE ERROR!");
			//ERRO PURAMENTE DB
		}
		dbclose();
		return $file_id;
	} else {
		dodebug("ERROR AT CREATION");
		// ERRO DB
		dbclose();
		return 0;
	}
	dodebug("WEIRD ERROR");
	dbclose();
	return 0;
}

?>