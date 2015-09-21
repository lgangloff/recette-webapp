<?php

require_once 'classes/DataConnector.class.php';

class UtilisateurDAO{

	public function getAllUtilisateurs(){
		$data_connector = new DataConnector();
		$datas = $data_connector->query(
		"	SELECT 		id_utilisateur, login, email, roles, enable
			FROM 		utilisateur
		");
		$data_connector->commit();
		return $datas;	
	}

	public function addUtilisateur($u){
		$u['crypt_password'] = mysql_escape_string(md5($u['login']));
		$u['login'] = mysql_escape_string($u['login']);
		$u['email'] = mysql_escape_string($u['email']);
		$u['roles'] = mysql_escape_string($u['roles']);
		
		
		$data_connector = new DataConnector();
		$data_connector->execute($data_connector->generateInsertStatement($u, "utilisateur"));		
		$id_utilisateur = $data_connector->getInsertId();
		
		$data_connector->commit();		
		return $id_utilisateur;
	}
	public function updateUtilisateurs($users){
		
		$data_connector = new DataConnector();
		foreach ($users as $id => $u) {
			$enable = mysql_escape_string($u['enable']);
			$email = mysql_escape_string($u['email']);
			$roles = mysql_escape_string($u['roles']);
			$data_connector->execute("
				UPDATE utilisateur SET 
					email = '$email', 
					enable = '$enable', 
					roles = '$roles'
				WHERE id_utilisateur = '$id'			
			");
		}
		$data_connector->commit();		
		return true;
	}
	
	public function getUtilisateurToLogin($login){
		$login_escape = mysql_escape_string($login);
		$data_connector = new DataConnector();
		$datas = $data_connector->query(
		"	SELECT 		id_utilisateur, login, crypt_password, email, roles
			FROM 		utilisateur
			WHERE 		login = '$login_escape' AND enable = '1'
		");
		$data_connector->commit();
		
		return count($datas) == 1 ? $datas[0] : null;	
	}
}

?>