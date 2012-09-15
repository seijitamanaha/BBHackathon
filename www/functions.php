<?php

$con_number = 0;
$con = null;
$debug = false;


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
	$username='bbhackathon';
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

function create_task() {
	dbconnect();
	if ($owner) {
		//dodebug("OWNER DEFINED");
		// Caso uma pasta não tenha sido definida, escolhe a home do usuário
		$query = "SELECT `home` FROM `bbhackathon`.`users` WHERE `users`.`id` = $owner;";
		$result = mysql_query($query, $con);
		if ($result && $uinfo = mysql_fetch_array($result)) {
			$homefolder = $uinfo['home'];
			//dodebug("USER HOMEFOLDER: $homefolder");
			if (!$folder) {
				//dodebug("FOLDER NOT DEFINED");
				$folder = $uinfo['home'];
			}
		} else {
			//dodebug("USER NOT FOUND");
			if (!$folder) {
				//dodebug("FOLDER NOT FOUND EXIT!");
				dbclose();
				return 0;
			} else {
				$owner = null;
			}
		}
	}
	$fid = ($folder != null ? $folder : $homefolder);
	//dodebug("FID = $fid");
	// Caso o dono não tenha sido definido, escolhe o dono da pasta
	$query = "SELECT `id`, `kids`, `owner` FROM `bbhackathon`.`file` WHERE `file`.`id` = $fid;";
	$result = mysql_query($query, $con);
	if ($result && $finfo = mysql_fetch_array($result)) {
		//dodebug("PARENT FOLDER FOUND");
		if (!$owner) {
			//dodebug("USER NOT DEFINED");
			$owner = $finfo['owner'];
		}
	} else {
		//dodebug("PARENT FOLDER NOT FOUND");
		if (!$owner || $fid == $homefolder) {
			//dodebug("USER NOT DEFINED");
			dbclose();
			return 0;
		} else  {
			//dodebug("SEARCHING USER HOME FOLDER");
			$folder = $homefolder;
			$query = "SELECT `id`, `kids`, `owner` FROM `bbhackathon`.`file` WHERE `file`.`id` = $folder;";
			if (!($result && $finfo = mysql_fetch_array($result))) {
				//dodebug("USER HOME FOLDER NOT FOUND");
				dbclose();
				return 0;
			}
		}
	}
	//dodebug("PRE-PROCESSING DONE");
	$attrstr = json_encode($attr);
	$typestr = write_str_array(array_keys($attr));
	$creation = gmdate("Y-m-d H:i:s");
	$folder = $finfo['id'];
	$query = "INSERT INTO  `bbhackathon`.`file` (
			`id`, `name`, `identifier`, `kids`, `parents`, `type`, `attr`, `privacy`, `owner`, `creation`
			) VALUES (
			NULL, '$name', '$identifier', '', '[1]$folder', '$typestr', '$attrstr', '', '$owner', '$creation');";
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