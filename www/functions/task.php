<?php

function create_task($json) {
	global $con;
	global $loguser;
	
	$jary = json_decode($json, true);
	$ret = array();
	
	if (!isset($jary['taskName'])) {
		$ret['error'] = Error::getError(Error::NO_TASKNAME);
		return json_encode($ret);
	}
	
	$name = $jary['taskName'];
	$deadline = isset($jary['taskDeadline']) ? "'".$jary['taskDeadline']."'" : "NULL";
	$complete = isset($jary['taskComplete']) ? $jary['taskComplete'] : "0";
	$type = isset($jary['taskType']) ? $jary['taskType'] : "0";
	$group = isset($jary['taskGroup']) ? $jary['taskGroup'] : "0";
	$privacy = isset($jary['taskPrivacy']) ? $jary['taskPrivacy'] : "0";
	$owner = isset($loguser['id']) ? $loguser['id'] : '0'; /* TODO PEGAR ID DO USUARIO LOGADO */
	$creation = gmdate("Y-m-d H:i:s");
	
	dbconnect();
	$query = "INSERT INTO  `bbhackathon`.`task` (
			`id`, `name`, `deadline`, `complete`, `type`, `group`, `privacy`, `owner`, `creation`
			) VALUES (
			NULL, '$name', $deadline, $complete, $type, $group, $privacy, $owner, '$creation'
			);";
	dodebug("DEBUG: QUERY: $query");
	$result = mysql_query($query, $con);
	if ($result) {
		$task_id = mysql_insert_id($con);
		if ($owner != 0) {
			// TODO UPDATE GROUP
			// TODO UPDATE USER
			$owner_array = json_decode($loguser['tasks'], true);
			if ($owner_array == null) $owner_array = array();
			$str = json_encode(array_push($owner_array, $task_id));
			$query = "UPDATE `bbhackathon`.`user` SET `user`.`tasks` = '$str' WHERE `user`.`id` = $owner LIMIT 1;";
			$result = mysql_query($query, $con);
			if (!$result) {
				// ERRO - ATUALIZAR USER
				dodebug("ERROR: KIDS UPDATE ERROR!");
				//ERRO PURAMENTE DB
				dbclose();
				return Error::getError(Error::SQL_QUERY);
			}
		}
		$ret['taskID'] = $task_id;
		dbclose();
		return $ret;
	} else {
		dodebug("ERROR AT CREATION");
		// ERRO DB
		dbclose();
		return Error::getError(Error::SQL_QUERY, "SQL QUERY: " . $query);
	}
	dodebug("WEIRD ERROR");
	dbclose();
	return Error::getError(Error::UNKNOWN);
}

function read_task($json) {
	
}

?>