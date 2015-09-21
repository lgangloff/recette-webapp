<?php

require_once 'classes/DataConnector.class.php';

class RecetteDAO{

	public function getRecette($id){
		$data_connector = new DataConnector();
		$datas = $data_connector->query("		
			SELECT 	id_recette, date_creation, titre, temps, modeoperatoire, resume, source,				
				IF(image is null, false, true) AS hasImage
			FROM	recette
			WHERE 	id_recette = $id
		");
		
		$datas_cat = $data_connector->query("		
			SELECT 	id_categorie, libelle, id_categorie_pere
			FROM	categorie c 
			WHERE 	EXISTS (	SELECT 1
								FROM	categorie_recette rc 
								WHERE	rc.id_categorie = c.id_categorie
								AND		rc.id_recette = $id )
			ORDER BY c.index
		");
		
		$datas_ing = $data_connector->query("		
			SELECT 	phase, nom, qte1, qte2
			FROM	ingredient
			WHERE 	id_recette = $id
			ORDER BY phase, nom
		");
		
		$datas_critere = $data_connector->query("
			SELECT 			c.libelle AS libelle, c.id_critere AS id_critere, if( valeur IS NULL , 0, valeur ) AS valeur
			FROM 			critere c
			LEFT OUTER JOIN	critere_recette cr ON cr.id_critere = c.id_critere AND cr.id_recette = $id
			ORDER BY		c.index
		");
		
		$data_connector->commit();
		
		$datas[0]["ingredients"] = $datas_ing;
		$datas[0]["categories"] = $datas_cat;
		$datas[0]["criteres"] = $datas_critere;
		return $datas[0];
	}
	
	public function getImage($recetteId){
		$data_connector = new DataConnector();
		$datas = $data_connector->query("		
			SELECT 	image
			FROM	recette
			WHERE 	id_recette = $recetteId
		");
		$data_connector->commit();
		
		return $datas[0]['image'];
	}
	
	public function getRecettesByCategorie($categorieId){
		
		$data_connector = new DataConnector();
		$datas = $data_connector->query("
		
			SELECT 	r.id_recette, date_creation, titre, temps, modeoperatoire, resume, 
				if( valeur IS NULL , 0, valeur ) AS note,				
				IF(image is null, false, true) AS hasImage
			FROM	recette r
			LEFT OUTER JOIN	critere_recette cr ON cr.id_recette = r.id_recette AND id_critere = 2
			WHERE 	EXISTS (	SELECT 1
								FROM	categorie_recette rc
								WHERE	rc.id_recette = r.id_recette
								AND		rc.id_categorie = '$categorieId' )
			ORDER BY date_creation DESC
		");
		$data_connector->commit();
		return $datas;
	}
	
	
	public function getLastRecettes($limit){
		
		$data_connector = new DataConnector();
		$datas = $data_connector->query("
		
			SELECT 	r.id_recette, date_creation, titre, temps, modeoperatoire, resume, 
				if( valeur IS NULL , 0, valeur ) AS note,				
				IF(image is null, false, true) AS hasImage
			FROM	recette r
			LEFT OUTER JOIN	critere_recette cr ON cr.id_recette = r.id_recette AND id_critere = 2
			ORDER BY date_creation DESC
			LIMIT 0, $limit
		");
		$data_connector->commit();
		return $datas;
	}
	
	public function addRecette($r){
		
		$r['titre'] = mysql_escape_string($r['titre']);
		$r['temps'] = mysql_escape_string($r['temps']);
		$r['modeoperatoire'] = mysql_escape_string($r['modeoperatoire']);
		$r['resume'] = mysql_escape_string($r['resume']);
		$r['source'] = mysql_escape_string($r['source']);
		$r['image'] = mysql_escape_string($r['image']);
	
		$data_connector = new DataConnector();
		$data_connector->execute($data_connector->generateInsertStatement($r, "recette"));
		
		$id_recette = $data_connector->getInsertId();
		
		foreach ($r['categorie'] as $key=>$value) {
			if ($key != 0){
				$value_escape = mysql_escape_string($key);
				$data_connector->execute("
					INSERT INTO categorie_recette(	`id_categorie`, 	`id_recette`) 
					VALUES						(	'$value_escape',	'$id_recette') ");
			}
		}
	
		foreach ($r['ingredient'] as $value) {
			$phase = mysql_escape_string($value['ingredient_phase']);
			$nom = mysql_escape_string($value['ingredient_nom']);
			$qte1 = mysql_escape_string($value['ingredient_qte1']);
			$qte2 = mysql_escape_string($value['ingredient_qte2']);
			$data_connector->execute("
				INSERT INTO ingredient (	`id_recette`, 	`phase`, `nom`, `qte1`,	`qte2`) 
				VALUES					(	'$id_recette',	'$phase','$nom','$qte1','$qte2') ");
		}
	
		foreach ($r['criteres'] as $key=>$value) {
			$id_critere = mysql_escape_string($key);
			$value_escape = mysql_escape_string($value);
			$data_connector->execute("
				INSERT INTO critere_recette (	`id_recette`, 	`id_critere`, `valeur`) 
				VALUES					(		'$id_recette',	'$id_critere','$value_escape') ");
		}
		
		
		
		$data_connector->commit();		
		return $id_recette;
	}
	
	public function updateRecette($r){
		
		if (!isset($r['id']) || $r['id'] == "" || !is_numeric($r['id'])){
			return false;
		}
		
		$r['titre'] = mysql_escape_string($r['titre']);
		$r['temps'] = mysql_escape_string($r['temps']);
		$r['modeoperatoire'] = mysql_escape_string($r['modeoperatoire']);
		$r['resume'] = mysql_escape_string($r['resume']);
		$r['source'] = mysql_escape_string($r['source']);
		$r['image'] = mysql_escape_string($r['image']);
	
		$data_connector = new DataConnector();
		$data_connector->execute("
			UPDATE recette SET 
				titre = '".$r['titre']."', 
				temps = '".$r['temps']."',
				modeoperatoire = '".$r['modeoperatoire']."',
				source = '".$r['source']."',
				resume = '".$r['resume']."' "
				. ($r['image'] != "" ? ", image = '".$r['image']."'"  : "") .
				
			"
				
			WHERE	id_recette = '".$r['id']."'
		
		");
		
		$id_recette = $r['id'];
		
		$data_connector->execute("DELETE FROM categorie_recette WHERE id_recette = '$id_recette'");
		
		foreach ($r['categorie'] as $key=>$value) {
			if ($key != 0){
				$value_escape = mysql_escape_string($key);
				$data_connector->execute("
					INSERT INTO categorie_recette(	`id_categorie`, 	`id_recette`) 
					VALUES						(	'$value_escape',	'$id_recette') ");
			}
		}
		
		$data_connector->execute("DELETE FROM ingredient WHERE id_recette = '$id_recette'");
		
		foreach ($r['ingredient'] as $value) {
			$phase = mysql_escape_string($value['ingredient_phase']);
			$nom = mysql_escape_string($value['ingredient_nom']);
			$qte1 = mysql_escape_string($value['ingredient_qte1']);
			$qte2 = mysql_escape_string($value['ingredient_qte2']);
			$data_connector->execute("
				INSERT INTO ingredient (	`id_recette`, 	`phase`, `nom`, `qte1`,	`qte2`) 
				VALUES					(	'$id_recette',	'$phase','$nom','$qte1','$qte2') ");
		}
		
		
		$data_connector->execute("DELETE FROM critere_recette WHERE id_recette = '$id_recette'");
		foreach ($r['criteres'] as $key=>$value) {
			$id_critere = mysql_escape_string($key);
			$value_escape = mysql_escape_string($value);
			$data_connector->execute("
				INSERT INTO critere_recette (	`id_recette`, 	`id_critere`, `valeur`) 
				VALUES					(		'$id_recette',	'$id_critere','$value_escape') ");
		}
		
		
		
		$data_connector->commit();		
	}
	
	public function deleteRecette($id){
		$data_connector = new DataConnector();
		$data_connector->execute("DELETE FROM ingredient WHERE id_recette = '$id'");
		$data_connector->execute("DELETE FROM categorie_recette WHERE id_recette = '$id'");
		$data_connector->execute("DELETE FROM critere_recette WHERE id_recette = '$id'");
		$data_connector->execute("DELETE FROM recette WHERE id_recette = '$id'");
	}
}

?>