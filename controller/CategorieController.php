<?php

function list_categorie($smarty){
	$catDAO = new CategorieDAO();
	$smarty->assign("categories", $catDAO->getSousCategories(null));
	
	$smarty->assign("include_page", "categories.tpl");
}

function update($smarty){	
	$items = explode(",",$_GET['saveString']);
	$catDAO = new CategorieDAO();
	$catDAO->updateHierarchy($items);
}
function add($smarty){
	$libelle = $_POST['libelle'];
	if (isset($libelle) && $libelle != ""){	
		$libelle = htmlspecialchars(stripcslashes($libelle));
		$catDAO = new CategorieDAO();
		$catDAO->addCategorie($libelle);	
	} 
	redirectTo("categorie");
}

function update_name($smarty){
	
	if (isset($_GET["renameId"]) && isset($_GET["newName"])){		
		$id = $_GET["renameId"];
		$new_name = htmlspecialchars(stripcslashes(utf8_decode($_GET["newName"])));
		if (is_numeric($id)){
			$catDAO = new CategorieDAO();
			if( !$catDAO->updateCategorie($id, $new_name)){
				echo "NOK";
				return;
			}
		}
	}
	
	echo "OK";
}

function delete($smarty){
	
	$items = explode(",",$_GET['deleteIds']);
	$catDAO = new CategorieDAO();
	if ($catDAO->deleteCategorie($items)){
		echo "OK";
	}
	else{		
		echo "NOK";
	}
	
}

?>