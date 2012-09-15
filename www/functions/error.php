<?php

final class Error {

	/** ERROR IDs **
	1 - taskname not defined

	*/

	const UNKNOWN = 0;
	const NO_TASKNAME = 1;
	const USER_LOGGED = 2;
	const NO_LOGIN = 3;
	const DB_NO_USER = 4;
	const WRONG_PASSWORD = 5;
	const SQL_QUERY = 6;
	
	public static function getErrorName($eid) {
		$str = "Erro $error_id : ";
		switch ($error_id) {
			case self::NO_TASKNAME:
				$str .= "Nome da task não definido";
				break;
			case self::NO_LOGIN:
				$str .= "Login não definido";
				break;
			case self::DB_NO_USER:
				$str .= "Usuario nao encontrado";
				break;
			case self::WRONG_PASSWORD:
				$str .= "Senha Incorreta";
				break;
			case self::SQL_QUERY:
				$str .= "Falha na conexao com o servidor";
				break;
			default:
				$str .= "Unknown";
				break;
		}
		return $str;
	}
	
	
	public static function getError($eid, $str = "") {
		return array('id'=>$eid, 'msg'=>self::getErrorName($eid)." - ".$str);
	}
}

?>