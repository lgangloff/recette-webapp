<?php session_start();
		
		

	$q = "";
	if (isset($_GET["q"])){
		$q = $_GET["q"];
		$offset = strpos($q, "?");
		if ($offset){
			$param = substr($q, $offset+1);
			$q = substr($q, 0, $offset);
			
			$offset = strpos($param, "=");
			$paramName = substr($param, 0, $offset);
			$paramValue = substr($param, $offset+1);
			$_GET[$paramName] = $paramValue;
		}	
	}

	$conf = new Config();
	$conf->parse_ini_file("./configs/config.ini");
	
	
	define('SMARTY_DIR', './smarty/libs/');
	require_once(SMARTY_DIR . 'Smarty.class.php');	
	$smarty = new Smarty();
	$smarty->template_dir = './templates/'.$conf->getTemplateDir().'/';
	$smarty->compile_dir = './smarty/templates_c/';
	$smarty->config_dir = './configs/';
	$smarty->cache_dir = './smarty/cache/';
	
	$smarty->plugins_dir[] = './classes/plugins/';
	
	$smarty->assign("contextpath", './templates/'.$conf->getTemplateDir());
	$smarty->assign("conf", $conf);
	
	$conf->invokeInitFunction($smarty);
	$conf->invokeMapping($q, $_SESSION,$smarty);
	$conf->invokeEndFunction($smarty);
	
		
	class Config {
		
		
		var $values = array();		
	
		function getTemplateDir(){
			return $this->values["configuration"]["template_dir"];
		}
	
		function invokeInitFunction($params = null){
			return $this->_invoke($this->values["configuration"]["init_function"], $params);
		}
		function invokeEndFunction($params = null){
			return $this->_invoke($this->values["configuration"]["end_function"], $params);
		}
		
		function invokeMapping($q, $sess, $params = null){
			$mapping = $this->getMapping($q);
			$mapping = ($mapping == null) ? $this->getDefaultMapping() : ( ($this->hasAccess($mapping, $sess)) ? $mapping : $this->getDefaultMapping() );
			return $this->_invoke($mapping, $params);
		}
		
		var $allReadyInclude = array();
		function _invoke($mapping, $params = null){
			if (is_array($mapping) && count($mapping) >= 2){
				$require = './controller/'.$mapping[0];
				if (!in_array($require, $this->allReadyInclude)){
					array_push($this->allReadyInclude, $require);
					require $require;
				}
				//echo "invoke ".$mapping[0]." => |" . $mapping[1]. "|<br>";
				return call_user_func($mapping[1], $params);
			}
			return false;
		}
		
		function hasAccess($map, $ses){
			return $map != null && ($map[2] == "ALL" || strpos($map[2], $this->getCurrentRole($ses)) !== false); 
		}
		
		function getCurrentRole($ses){
			
			if (isset($ses[$this->values["configuration"]["attribute_name_session_role"]]))
				return $ses[$this->values["configuration"]["attribute_name_session_role"]];
			else
				return "PUBLIC";
		}
		
		function getMapping($q){
			return isset($this->values["mapping"]["/".$q]) ? $this->values["mapping"]["/".$q] : null;
		}
		
		function getDefaultMapping(){
			return $this->values["mapping"]["default"];
		}
		
		function parse_ini_file($filepath) {
			if (is_file($filepath) && ($lines = file($filepath)) != false) {
				for($i = 0; $i < count($lines); $i++){
					$line = $lines[$i];
					if (substr($line, 0, 1) != "#" && (substr($line, 0, 1) == "[" || strpos($line, "=") != false)){
						
						if (substr($line, 0, 1) == "["){
							if (!empty($sectionname)){
								$this->values[$sectionname] = $sectionvalue;
							}
							$sectionvalue = array();
							$sectionname = substr($line, 1, strlen($line)-4);
						}
						else{
							$key = trim(substr($line, 0, strpos($line, "=")));
							$value = trim(substr($line, strpos($line, "=")+1), " \t");
							$value = str_replace("\t", " ", $value);
							$count = 1;
							while($count != 0){
								$value = str_replace("  ", " ", $value, $count);
							}
							$value = trim($value);
							if (strpos($value, " ")){
								$value = explode(" ", trim($value));
							}
							$sectionvalue[$key] = $value;
						}
					}
				}
				$this->values[$sectionname] = $sectionvalue;
			}
			//print_r($this->values);
		}
	}
?>