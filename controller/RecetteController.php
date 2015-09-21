<?php



function display_recette($smarty){
	$id = (isset($_GET['id']) && is_numeric($_GET['id'])) ? $_GET['id'] : 0;
	
	if ($id == 0){
		return index($smarty);
	}
	else{		
		$recetteDAO = new RecetteDAO();	
		$recette = $recetteDAO->getRecette($id);
		$smarty->assign("recette",$recette);
		$smarty->assign("include_page", "recettes/display_recette.tpl");
	}
	
}

function liste_recette($smarty){
	$cat_id = (isset($_GET['categorie']) && is_numeric($_GET['categorie'])) ? $_GET['categorie'] : 0;
	if ($cat_id != 0){
		$recetteDAO = new RecetteDAO();	
		$recettes = $recetteDAO->getRecettesByCategorie($cat_id);
		$smarty->assign("recettes", $recettes);
	}
	
	$smarty->assign("include_page", "recettes/list_recette.tpl");	
}

function load_choice($smarty){
	
	//recuperation des données de la recette à afficher
	$recette = $smarty->getTemplateVars('recette');
	
	$catDAO = new CategorieDAO();
	$smarty->assign("sub_categories",$catDAO->getSousCategories(null));
	
		
	$critDAO = new CritereDAO();
	$smarty->assign("criteres", $critDAO->getAllCriteres());
	
	
}

function create_recette($smarty){
	
	$smarty->assign("mode", "creation");
	
	
	$recette = $smarty->getTemplateVars('recette');
	if (!isset($recette)){
		$ingredients = array();
		for ($i = 0; $i < 1; $i++) {
			$ing = array(
				'ingredient_phase' => '',
				'ingredient_nom' => '',
				'ingredient_qte1' => '',
				'ingredient_qte2' => '',
			);
			array_push($ingredients, $ing);
		}
		$recette = array();
		$recette["ingredient"] = $ingredients;
		$smarty->assign("recette", $recette);
	}
	
	load_choice($smarty);
	
	
	$smarty->assign("include_page", "recettes/edit_recette.tpl");
	
}

function delete_recette($smarty){
	
	$id = (isset($_GET['id']) && is_numeric($_GET['id'])) ? $_GET['id'] : 0;
	if ($id != 0){
		$recDAO = new RecetteDAO();
		$recDAO->deleteRecette($id);	
		
		$message = array();
		array_push($message, "La recette a bien été supprimée.");
		$smarty->assign("message", $message);
		
		return index($smarty);
	
	}
}

function edit_recette($smarty){
	
	$smarty->assign("mode", "edition");
	
	$id = (isset($_GET['id']) && is_numeric($_GET['id'])) ? $_GET['id'] : 0;
	
	$recette = $smarty->getTemplateVars('recette');
	if (!isset($recette)){
		if ($id == 0){
			return index($smarty);
		}
		else{		
			$recetteDAO = new RecetteDAO();	
			$recette = $recetteDAO->getRecette($id);
	
			$recette_ingredient = array();
			for ($i = 0; $i < count($recette['ingredients']); $i++) {
				$ing = array(
					'ingredient_phase' => htmlspecialchars($recette['ingredients'][$i]['phase']),
					'ingredient_nom' => htmlspecialchars($recette['ingredients'][$i]['nom']),
					'ingredient_qte1' => htmlspecialchars($recette['ingredients'][$i]['qte1']),
					'ingredient_qte2' => htmlspecialchars($recette['ingredients'][$i]['qte2'])
				);
				array_push($recette_ingredient, $ing);
			}
			$recette['ingredient'] = $recette_ingredient;
			
			$recette_categorie = array();
		
			for ($i = 0; $i < count($recette['categories']); $i++) {
				if ($recette['categories'][$i]['id_categorie_pere']==""){
					$recette_categorie[0] = $recette['categories'][$i]['id_categorie'];
				}
				$recette_categorie[$recette['categories'][$i]['id_categorie']] = "true";
			}
			$recette['categorie'] = $recette_categorie;
			
			$recette_criteres = array();
			
			for ($i = 0; $i < count($recette['criteres']); $i++) {
				$recette_criteres[$recette['criteres'][$i]['id_critere']] = $recette['criteres'][$i]['valeur'];
			}
			$recette['criteres'] = $recette_criteres;
		}	
	}
	
	//print_r($recette);
	$smarty->assign("recette",$recette);
	load_choice($smarty);
	$smarty->assign("include_page", "recettes/edit_recette.tpl");
}

function save_recette($smarty){
	
	//Récupération des catégories
	$recette_categorie = array();
	if (is_array($_POST['recette_categorie'])){
		$recette_categorie[0] =  $_POST['recette_categorie'][0];
		foreach ($_POST['recette_categorie'] as $cat){
			$recette_categorie[$cat] = "true";
		}
	}

	//Récupération des ingrédients
	$recette_ingredient = array();
	if (is_array($_POST['ingredient_phase'])){
		for ($i = 0; $i < count($_POST['ingredient_phase']); $i++) {
			$ing = array(
				'ingredient_phase' => htmlspecialchars(stripcslashes($_POST['ingredient_phase'][$i])),
				'ingredient_nom' => htmlspecialchars(stripcslashes($_POST['ingredient_nom'][$i])),
				'ingredient_qte1' => htmlspecialchars(stripcslashes($_POST['ingredient_qte1'][$i])),
				'ingredient_qte2' => htmlspecialchars(stripcslashes($_POST['ingredient_qte2'][$i]))
			);
			array_push($recette_ingredient, $ing);
		}
	}
	//Récupération des critères
	$recette_critere = array();
	$all_post_name = array_keys($_POST);
	foreach ($all_post_name as $value) {
		if (substr($value, 0, 5) == "note_"){
			$id_critere = substr($value, 5);
			$post_value = (isset($_POST[$value]) && is_numeric($_POST[$value])) ? $_POST[$value] : 0;
			$recette_critere[$id_critere] = $post_value;
		}
	}

	$filename_temp = htmlspecialchars($_POST['recette_image_tmp']);
	if (isset($_FILES['recette_image_upload']) && $_FILES['recette_image_upload']['error'] == 0){
		$filename_temp = "./files/". time();
		move_uploaded_file($_FILES['recette_image_upload']['tmp_name'], $filename_temp);		
	}
	
	//récupération des données de la recette
	$recette = array();	
	$recette['id'] = htmlspecialchars($_POST['recette_id']);
	$recette['titre'] = htmlspecialchars(stripcslashes($_POST['recette_titre']));
	$recette['temps'] = htmlspecialchars(stripcslashes($_POST['recette_temps']));
	$recette['source'] = htmlspecialchars(stripcslashes($_POST['recette_source']));
	$recette['modeoperatoire'] = stripcslashes($_POST['recette_modeoperatoire']);
	$recette['resume'] = stripcslashes($_POST['recette_resume']);
	$recette['image_tmp'] = $filename_temp;
	$recette['categorie'] = $recette_categorie;
	$recette['ingredient'] = $recette_ingredient;
	$recette['criteres'] = $recette_critere;
	
	
	//Action sans sauvegarde		
	if ($_POST["action"] == "change_cat"){
		//On fait rien, le reload le détecte après
	}
	//Ajout d'un ingrédient
	else if ($_POST["action"] == "ajoute_ing"){
		$ing = array(
			'ingredient_phase' => '',
			'ingredient_nom' => '',
			'ingredient_qte1' => '',
			'ingredient_qte2' => ''
		);
		array_push($recette['ingredient'], $ing);
	}
	//Suppression d'un ingrédient
	else if ($_POST["action"] == "del_ing" && isset($_POST["param_del"]) && is_numeric($_POST["param_del"])){
		$index_remove = $_POST["param_del"];
		$new_recette_ingredient = array();
		for ($j = 0; $j < count($recette_ingredient); $j++) {
			if ($j != $index_remove){
				array_push($new_recette_ingredient, $recette_ingredient[$j]);
			}
			$recette['ingredient'] = $new_recette_ingredient;
		}
	}
		
	//Sauvegarde mémoire des données	
	$smarty->assign("recette", $recette);
		
	//Action sans sauvegarde ? => on recharge juste le formulaire
	if(isset($_POST["action"]) && $_POST["action"] != ""){
		if (isset($recette['id']) && is_numeric($recette['id'])){
			return edit_recette($smarty);
		}
		else{
			return create_recette($smarty);
		}
	}
	
	
	//Vérification des données
	$erreur = array();
	if (!isset($recette['titre']) || strlen($recette['titre']) <= 0){
		array_push($erreur, "Le titre est obligatoire");
	}/*
	if (!isset($recette['difficulte']) || strlen($recette['difficulte']) <= 0){
		array_push($erreur, "La difficulté est obligatoire");
	}*/
	if (!isset($recette['categorie'][0]) || !is_numeric($recette['categorie'][0])){
		array_push($erreur, "Sélectionner au moins la catégorie principale");
	}
	if (isset($recette['ingredient'])){
		for ($j = 0; $j < count($recette['ingredient']); $j++) {
			if (strlen($recette['ingredient'][$j]['ingredient_nom'])<=0){
				array_push($erreur, "Le nom de l'ingrédient n°".($j+1)." est obligatoire");
			}
		}
	}
	$smarty->assign("erreur", $erreur);
	
	
	$recette['image'] = '';
	if ($recette['image_tmp'] != ""){
		//TODO: hmm hmm
		$handle = fopen($recette['image_tmp'], 'r');
		$data = fread($handle, filesize($recette['image_tmp']));
		fclose($handle);
		$recette['image'] = $data;
	}	

	
	$recDAO = new RecetteDAO();
	//Persistence en bdd
	if (isset($recette['id']) && is_numeric($recette['id'])){
		if (count($erreur) > 0){
			return edit_recette($smarty);
		}
		else{
			$temp = $recette['image_tmp'];
			$recette['image_tmp'] = '';
			$recDAO->updateRecette($recette);
			$recette['image_tmp'] = $temp;
			return redirectTo("recette/display?id=".$recette['id']."&categorie=".$_GET["categorie"]);
		}
	}
	else{
		if (count($erreur) > 0){
			return create_recette($smarty);
		}
		else{
			$temp = $recette['image_tmp'];
			$recette['image_tmp'] = '';
			$id = $recDAO->addRecette($recette);
			$recette['image_tmp'] = $temp;
			return redirectTo("recette/display?id=".$id."&categorie=".$_GET["categorie"]);
		}
	}
}

function show_image($smarty){
	header("Content-Type: image/png");
	if (isset($_GET["id_recette"]) && is_numeric($_GET["id_recette"])){
		$id_recette = $_GET["id_recette"];
		$recDAO = new RecetteDAO();
		$image = $recDAO->getImage($id_recette);
		if ($image){
			echo $image;
			return;
		}
	}
	echo "";
}

?>