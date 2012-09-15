<?php

function register($json) {
	
}
	
function logout() {
	setcookie('user', '', time()-3600);
	return "{}";
}

function login($json) {
	global $con;
	$jary = json_decode($json, true);
	$ret = array();
	if (isset($_COOKIE["user"]))
	{
		$user = userinfo();
		$ret['id'] = $_COOKIE["user"];
		$ret['error'] = Error::getError(Error::USER_LOGGED);
	} else if (isset($jary['userLogin'])) {
		$login = $jary['userLogin'];
		$password = $jary['userPassword'];
		if (substr_count($login, '@') == 0) {
			$query = "SELECT * from `bbhackathon`.`user` WHERE `username` = '$login';";
		} else {
			$query = "SELECT * from `bbhackathon`.`user` WHERE `email` = '$login';";
		}
		dbconnect();
		$result = mysql_query($query, $con);
		if($result && $row = mysql_fetch_array($result)) {
			if (crypt($password, $row["password"]) == $row["password"]) { // ($password == $row["password"]) { //
				$leresult = setcookie("user", $row["id"], time()+3600*24*7);
				$ret['userId'] = $row["id"];
			} else {
				// ERRO - SENHA INCORRETA
				$ret['error'] = Error::getError(Error::WRONG_PASSWORD);
			}
		}
		else
		{
			// ERRO - USUARIO NAO ENCONTRADO
			$ret['error'] = Error::getError(Error::DB_NO_USER);
		}
		dbclose();
		$user = userinfo();
	} else {
		// ERRO - FALTA ATRIBUTOS
		$ret['error'] = Error::getError(Error::NO_LOGIN);
	}
	return $ret;
}

function userinfo($id = null) {
	global $con;
	
	if ($id == null && !isset($_COOKIE["user"]) && isset($_REQUEST['json'])) {
		$jary = json_decode(stripslashes($_REQUEST['json']), true);
		if (isset($jary['logID'])) {
			$id = $jary['logID'];
		}
	}
	
	// checa se o usuario esta logado e retorna uma array com as informacoes do usuario
	if (isset($_COOKIE["user"]) || $id != null)
	{
		$user = null;
		// define que atributos serão salvos na variavel, e encontra o tamanho da array
		$row_names = array('id', 'username', 'email', 'name', 'gender', 'birthday', 'tasks', 'groups');
		$size = count($row_names, 0);
		
		// conecta com a database
		$con = dbconnect();
		
		// pega o userid
		//$userid = $_COOKIE['user']; se necessario logar para pegar a info, checar se esse cookie esta setado
		
		if ($id == null) {
			$id = $_COOKIE['user'];
		}
		
		// seleciona os dados definidos acima do usuario
		$query = "SELECT ";
		for ($i = 0; $i < $size; $i++)
		{
			if ($i > 0) {
				$query .= ", ";
			}
			$query .= "`" . $row_names[$i] . "`";
		}
		$query .= " from `bbhackathon`.`users` WHERE `id` = " . $id . ";";
		$result = mysql_query($query, $con);
		if($result && $row = mysql_fetch_array($result)) {
			//$user_command = "new setuser(";
			$user = $row;
			
		}
		else
		{
			// caso a query nao tenha encontrado o userid, define que nao foi logado
			$user = null;
		}
		// os atributos da variavel $user devem ser acessados como uma array (ex: $user['email'])
		
		// fecha a conexao
		dbclose();
	}
	else
	{
		// caso o cookie nao exista e o user seja o logado, define o user como null e logged como false
		$user = null;
	}
	return $user;
}

$loguser = userinfo();

?>