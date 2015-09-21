<?php

require_once 'classes/DataConnector.class.php';

class CategorieDAO{

	public function getCategorie($id){
		$data_connector = new DataConnector();
		$datas = $data_connector->query(
		"	SELECT 		cat.id_categorie, cat.libelle 
			FROM 		categorie cat
			WHERE 		cat.id_categorie = $id
		");
		$data_connector->commit();
		return $datas[0];	
	}
	
	public function getPrincipalesCategories(){
		$data_connector = new DataConnector();
		$datas = $data_connector->query(
		"	SELECT 		cat.id_categorie, cat.libelle 
			FROM 		categorie cat
			WHERE 		cat.id_categorie_pere IS NULL
			ORDER BY	cat.index		
		");
		$data_connector->commit();
		return $datas;		
	}

	public function getHierarchie($id_categorie){	
		$categorie = $this->getCategorie($id_categorie);	
		$data_connector = new DataConnector();
		$datas = array();
		$datas = $this->_getParent($data_connector, $datas, $categorie);
		$data_connector->commit();
		return $datas;	
	}
	private function _getParent($data_connector, $datas, $categorie){
		$id_categorie = $categorie["id_categorie"];
		
		array_push($datas, $categorie);
		
		$res = $data_connector->query("
			SELECT cat.id_categorie_pere AS pere, pere.libelle AS libelle 
			FROM categorie cat
			LEFT OUTER JOIN categorie pere ON cat.id_categorie_pere = pere.id_categorie
			WHERE cat.id_categorie = '$id_categorie'
		");
		if (isset($res[0]["pere"]))
			return $this->_getParent($data_connector, $datas, array(
																'id_categorie'=>$res[0]["pere"],
																'libelle'=>$res[0]["libelle"]));
		else
			return $datas;	
	}
	
	public function getChilds($id_categorie){
		$data_connector = new DataConnector();
		$datas = $data_connector->query(
			"	SELECT 		cat.id_categorie, cat.libelle
				FROM 		categorie cat
				WHERE 		cat.id_categorie_pere = $id_categorie			
				ORDER BY	cat.index, cat.libelle		
			");
		$data_connector->commit();
		return $datas;
	}
	
	public function getSousCategories($id_categorie){
		$datas = array();
		$data_connector = new DataConnector();
		$datas = $this->_getSousCategories($data_connector, $id_categorie);
		$data_connector->commit();
		return $datas;
	}
	private function _getSousCategories($data_connector, $id_categorie){
		if (isset($id_categorie) && !is_numeric($id_categorie))
			return false;
		else if (!isset($id_categorie)){			
			$data = $data_connector->query(
			"	SELECT 		cat.id_categorie, cat.libelle
				FROM 		categorie cat
				WHERE 		cat.id_categorie_pere is null			
				ORDER BY	cat.index, cat.libelle		
			");
		}
		else{			
			$data = $data_connector->query(
			"	SELECT 		cat.id_categorie, cat.libelle
				FROM 		categorie cat
				WHERE 		cat.id_categorie_pere = $id_categorie			
				ORDER BY	cat.index, cat.libelle		
			");
		}
			
		
		foreach ($data as $key=>$value) {
			$data[$key]['fils'] = $this->_getSousCategories($data_connector, $value['id_categorie']);
		}
		return $data;
	}
	
	public function updateHierarchy($items){
	
		$data_connector = new DataConnector();
		
		for($no=0;$no<count($items);$no++){
			$tokens = explode("-",$items[$no]);
			if($tokens[0] == 0) $tokens[0] = "NULL";
			if($tokens[1] == 0) $tokens[1] = "NULL";
			$data_connector->execute("UPDATE categorie cat SET cat.id_categorie_pere = $tokens[1], cat.index = '$no' WHERE cat.id_categorie = '$tokens[0]' ");
		}
		$data_connector->commit();
	}
	
	public function addCategorie($libelle){
		$libelle_escape = mysql_escape_string($libelle);
		$data_connector = new DataConnector();
		$data_connector->execute("INSERT INTO categorie(`libelle`) VALUES ('$libelle_escape')");
		$data_connector->commit();
	}

	public function updateCategorie($id, $new_name){
		$libelle_escape = mysql_escape_string($new_name);
		$data_connector = new DataConnector();
		$count = $data_connector->execute("UPDATE categorie cat SET cat.libelle='$libelle_escape' WHERE cat.id_categorie = '$id'");
		$data_connector->commit();
		return $count == 1;
	}

	public function deleteCategorie($ids){
		
		$count = 0;
		$data_connector = new DataConnector();		
		for($no=count($ids)-1;$no>=0;$no--){
			$id=$ids[$no];
			if(is_numeric($id)){
				$c = $data_connector->execute("DELETE FROM categorie WHERE id_categorie = '$id'");
				$count += $c;
			}
		}
		$data_connector->commit();
		return $count == count($ids);
	}
}

?>