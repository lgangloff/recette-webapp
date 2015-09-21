<?php

require_once 'classes/dao/CategorieDAO.class.php';
require_once 'classes/dao/UtilisateurDAO.class.php';
require_once 'classes/dao/RecetteDAO.class.php';
require_once 'classes/dao/CritereDAO.class.php';

function traitement_initial($smarty){
	
	$catDAO = new CategorieDAO();
	$smarty->assign("principalesCategories", $catDAO->getPrincipalesCategories());
	
	$categorie_id = (isset($_GET['categorie']) && is_numeric($_GET['categorie'])) ? $_GET['categorie'] : 0;

	if ($categorie_id != 0){
		$filariane = $catDAO->getHierarchie($categorie_id);
		
		$smarty->assign("inv_filariane", $filariane);
		$filariane = array_reverse($filariane);		
		$smarty->assign("filariane", $filariane);
		
		$souscategories = array();
		foreach ($filariane as $val){
			$souscategories[$val["id_categorie"]] = $catDAO->getChilds($val["id_categorie"]);
		}
		$smarty->assign("souscategories", $souscategories);
	}
}

function traitement_final($smarty){
	$tmp = $smarty->getTemplateVars('include_page');
	if (isset($tmp)){
		$smarty->display("template.tpl");
	}
}

function index($smarty){
	
	$recetteDAO = new RecetteDAO();
	$recettes = $recetteDAO->getLastRecettes(6);
	$smarty->assign("recettes", $recettes);
	$smarty->assign("include_page", "accueil.tpl");
}

function login($smarty){
	
	$uDAO = new UtilisateurDAO();
	
	$user = $uDAO->getUtilisateurToLogin(htmlspecialchars($_POST["login"]));
	if (md5($_POST["password"]) == $user["crypt_password"]){	
		$_SESSION["role"] = $user['roles'];
		$_SESSION["login"] = $user['login'] . '('.$user['roles'].')';
	}
	else{
		$smarty->assign("erreur", array("Mauvais login ou mot de passe"));
	}
	
	return index($smarty);
}
function disconnect($smarty){
	
	session_unset();
	session_destroy();
	
	return index($smarty);
}

function redirectTo($page){
	$host  = $_SERVER['HTTP_HOST'];
	$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
	header("Location: http://$host$uri/index.php?q=$page");
	exit;
}

?>