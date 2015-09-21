<?php

require_once 'classes/DataConnector.class.php';

class CritereDAO{

	public function getAllCriteres(){
		$data_connector = new DataConnector();
		$datas = $data_connector->query("		
			SELECT 	id_critere, libelle
			FROM	critere c
			ORDER BY c.index
		");
		
		$data_connector->commit();
		
		return $datas;
	}
}

?>