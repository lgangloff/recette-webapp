<?php

require_once 'classes/dao/UtilisateurDAO.class.php';

function list_utilisateur($smarty){
	$uDAO = new UtilisateurDAO();
	$smarty->assign("utilisateurs", $uDAO->getAllUtilisateurs());
	
	$smarty->assign("include_page", "utilisateurs.tpl");
}


function update($smarty){
	$users = array();
	for ($i = 0; $i < count($_POST["utilisateurs_id"]); $i++) {
		$id = $_POST["utilisateurs_id"][$i];
		if (is_numeric($id)){
			$users[$id] = array(
				'email' => htmlspecialchars($_POST["utilisateurs_email"][$i]),
				'roles' => htmlspecialchars($_POST["utilisateurs_roles"][$i]),
				'enable' => htmlspecialchars($_POST["utilisateurs_enable"][$i]),
			);
		}
	}

	$uDAO = new UtilisateurDAO();
	if ($uDAO->updateUtilisateurs($users)){		
		$smarty->assign("message", array('Utilisateurs mis à jour'));
		//TODO: envoie email
	}
	return list_utilisateur($smarty);
}	
function add($smarty){
	
	$u = array();	
	$u['login'] = htmlspecialchars(stripcslashes($_POST['utilisateur_login']));
	$u['email'] = htmlspecialchars(stripcslashes($_POST['utilisateur_email']));
	$u['roles'] = htmlspecialchars($_POST['utilisateur_role']);
	
	//Vérification des données
	$erreur = array();
	if (!isset($u['login']) || strlen($u['login']) <= 0){
		array_push($erreur, "Le nom d'utilisateur est obligatoire");
	}
	if (!isset($u['email']) || strlen($u['email']) <= 0){
		array_push($erreur, "Le mail est obligatoire");
	}
	//TODO: Tester le format du mail
	$smarty->assign("erreur", $erreur);
	
	if (count($erreur) > 0){
		$smarty->assign("utilisateur", $u);
	}
	else{
		$uDAO = new UtilisateurDAO();
		if ($uDAO->addUtilisateur($u)>0){		
			$smarty->assign("message", array('Utilisateur ajouté.'));
			//TODO: envoie email
		}
	}
	
	return list_utilisateur($smarty);
}

?>