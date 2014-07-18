<?php


class Blog extends ModelMysqlImpl{
	
	
	protected $moduleName = 'blog';
	
	 
	public function setModuleName($value) {
		$this->moduleName = $value;
	}
	
	public function getModuleName(){
		return $this->moduleName;
	}

		
	public function all() {
		$result = array();
		$total = 0;
		try {
			$isPage = $_REQUEST['offset'];			
			$sql = "SELECT * FROM ".$_REQUEST['model'];
			if(isset($isPage)) {
				$_sql = "SELECT COUNT(*) AS total FROM ".$_REQUEST['model'];
				$_data = SQLHandler::executeSQL($this->conn, 'get', $_sql);
				$total = $_data[0]["total"];
				$offset = $_REQUEST['offset'];
				$size = $_REQUEST['size'];
				$sql = $sql." LIMIT ".$offset.",".$size.";";					
			}
			$data = SQLHandler::executeSQL($this->conn, 'get', $sql);
			$result = array("sql"=>$sql,"payload"=>$data,"total"=>$total);
		} catch(Exception $e){
			$result = array("error"=>$e->getMessage());
		}
		return $result;
	}
	
}

?>
