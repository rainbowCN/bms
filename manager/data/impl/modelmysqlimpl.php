<?php

class ModelMysqlImpl implements Model{
	
	protected $dbHandler;
	protected $conn;
	
	public function __construct() {
		$this->dbHandler = new DBHandler();
		$this->conn = $this->dbHandler->getConn();
	}
	public function getConn(){
		return $this->conn;
	}
	public function getDBHandler(){
		return $this->dbHandler;
	}

	public function create() {
		$result = null;
		try {
			$_id = -1;
			$_p_sql = "";
			$_v_sql = "";
			foreach($_REQUEST['data'] as $key=>$value) {
				if($key=='id') {
					unset($_REQUEST['data'][$key]);
					continue;
				}
				$_p_sql = $_p_sql.",".$key;
				$_v_sql = $_v_sql.",:".$key;
			}
			$sql = "INSERT INTO ".$_REQUEST['model']."(".substr($_p_sql,1).") VALUES (".substr($_v_sql,1).");";
			SQLHandler::executeSQL($this->conn, 'put', $sql, $_REQUEST['data']);
			$result = $this->conn->lastInsertId();
		} catch(Exception $e){
			$result = array("error"=>$e->getMessage());
			//throw $e;
		}
		return $result;
	}
	
	public function remove($id) {
		$result = array("exec"=>true);
		try {
			$sql = "DELETE FROM ".$_REQUEST['model']." WHERE id=:id";
			SQLHandler::executeSQL($this->conn, 'delete', $sql, array("id"=>$id));
		} catch(Exception $e){
			$result = array("error"=>$e->getMessage());
		}
		return $result;
	}
	
	
	public function update($id) {
		$result = array("exec"=>true);
		try {
			$_sql = "";
			foreach($_REQUEST['data'] as $key=>$value) {
				if($key=='_id')
					continue;
				$_sql = $_sql.",".$key."=:".$key;
			}
			$sql = "UPDATE ".$_REQUEST['model']." SET ".substr($_sql,1)." WHERE id=:_id";
			SQLHandler::executeSQL($this->conn, 'put', $sql, $_REQUEST['data']);
		} catch(Exception $e){
			throw $e;
		}
		return $result;
	}

	public function all() {
		$result = array();
		try {
			$sql = "SELECT * FROM ".$_REQUEST['model'];
			$result = SQLHandler::executeSQL($this->conn, 'get', $sql);
		} catch(Exception $e){
			$result = array("error"=>$e->getMessage());
		}
		return $result;
	}
	
	
    public function read($id) {
    	$result = array();    	
    	try {
    		$sql = "SELECT * FROM ".$_REQUEST['model']." WHERE id=".$id;
    		$res = SQLHandler::executeSQL($this->conn, 'get', $sql);
    		$result = $res[0];
    	} catch(PDOException $e){
    		$result = array("error"=>$e->getMessage());
    	}
		return $result;	
	}
	
}

