<?php


class DataConnector{

	private $link_identifier;
	public $hasError;
	
	public function __construct(){
		$this->link_identifier = mysql_connect("127.0.0.1","root",'');
		mysql_select_db("mydb", $this->link_identifier);
	}

	public function begin(){
	
	}	
	public function commit(){
		mysql_close($this->link_identifier);
	}
	public function rollback(){
	
	}
	
	public function query($query){
		$tabs = array();
		$result = mysql_query($query, $this->link_identifier) or die("Erreur MySQL: " . mysql_error());;
		while(($row = mysql_fetch_assoc($result))){
			array_push($tabs, $row);
		}
		return $tabs;
	}
	
	public function execute($query){
		//echo $query."<br/>";
		mysql_query($query, $this->link_identifier) or die("Erreur MySQL: " . mysql_error());
		return mysql_affected_rows ($this->link_identifier);
	}
	
	public function getInsertId(){
		return mysql_insert_id();
	}
	
	
	public function generateInsertStatement($data, $table){
		$col = "";
		$val = "";		
		
		foreach ($data as $key=>$value) {
			if (isset($value) && !is_array($value) && $value != ''){							
				if ($col != ""){
					$col .= ",";
					$val .= ",";
				}
				$col .= "`".$key."`";
				$val .= "'".$value."'";
			}
		}
		
		$query = "INSERT INTO $table (".$col.") VALUES (".$val.")";
		return $query;
	}
}


?>