<?php


class User extends ModelMysqlImpl{
	
	
	protected $moduleName = 'user';
	
	 
	public function setModuleName($value) {
		$this->moduleName = $value;
	}
	
	public function getModuleName(){
		return $this->moduleName;
	}

	public function create() {
		$result = null;
		try {
			$_p_sql = "";
			$_v_sql = "";
			foreach($_REQUEST['data'] as $key=>$value) {
				if($key=='image'||$key=='file') {
					unset($_REQUEST['data'][$key]);
					continue;
				}
				if($key=='name'){
					$username = $_REQUEST['data']['name'];
				}
				$_p_sql = $_p_sql.",".$key;
				$_v_sql = $_v_sql.",:".$key;
			}
			$this->cook($_p_sql, $_v_sql);
			$sql = "INSERT INTO ".$_REQUEST['model']."(".substr($_p_sql,1).") VALUES (".substr($_v_sql,1).");";
			SQLHandler::executeSQL($this->conn, 'put', $sql, $_REQUEST['data']);
			$result = $this->conn->lastInsertId();
		} catch(Exception $e){
			throw $e;
		}
		return $result;
	}
	
	private function cook(&$_p_sql, &$_v_sql) {
		$username = "";
		$password = "123456";
		$salt = "bms";
		foreach($_REQUEST['data'] as $key=>$value) {
			if($key=='name'){
				$username = $_REQUEST['data']['name'];
				break;
			}
		}
		$_p_sql = $_p_sql.",password";
		$_v_sql = $_v_sql.",:password";
		$_REQUEST['data']['password'] = md5($password.$salt);
			
		$accessToken = md5($password);
		$_p_sql = $_p_sql.",accessToken";
		$_v_sql = $_v_sql.",:accessToken";
		$_REQUEST['data']['accessToken'] = $accessToken;
				
		$token = md5($accessToken.$salt);
		$_p_sql = $_p_sql.",token";
		$_v_sql = $_v_sql.",:token";
		$_REQUEST['data']['token'] = $token;		
	}
	
	public function update($id) {
		$result = array("exec"=>true);
		try {
			$_sql = "";
			foreach($_REQUEST['data'] as $key=>$value) {
				if($key=='_id'||$key=='image'||$key=='file') {
					unset($_REQUEST['data'][$key]);
					continue;
				}
				$_sql = $_sql.",".$key."=:".$key;
			}
			$sql = "UPDATE ".$_REQUEST['model']." SET ".substr($_sql,1)." WHERE id=:id";
			SQLHandler::executeSQL($this->conn, 'put', $sql, $_REQUEST['data']);
		} catch(Exception $e){
			throw $e;
		}
		return $result;
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
			$result = array("payload"=>$data,"total"=>$total);
		} catch(Exception $e){
			$result = array("error"=>$e->getMessage());
		}
		return $result;
	}
		
	public function unique($args) {
		$result = array("unique"=>false);
		try {
			$sql = "SELECT * FROM bms_user WHERE name=:name";
			$rows = SQLHandler::executeSQL($this->conn, 'post', $sql, $args);
			if(count($rows)>0){
				$result = array("unique"=>true);
			}
		} catch(Exception $e){
			throw $e;
		}
		return $result;
	}
	
	/**
	 * Process the login in.
	 * @param unknown $args
	 * @throws Exception
	 * @return multitype:boolean
	 */
	public function login($args) {
		$result = array("isLogin"=>false);
		try {
			$sql = "SELECT * FROM bms_user WHERE name=:name AND password=:password";
			$args["password"] = md5($args["password"].md5($args["name"]));
			$rows = SQLHandler::executeSQL($this->conn, 'post', $sql, $args);
			if(count($rows)>0){
				$_SESSION["isLogin"] = true;
				$_SESSION["user"] = $rows[0];
				$result = array("isLogin"=>$_SESSION["isLogin"]);
			}			
		} catch(Exception $e){
			throw $e;
		}
		return $result;
	}	
	
	/**
	 * Process the login out
	 * @throws Exception
	 * @return multitype:boolean
	 */
	public function logout(){
		$result = array("isLogin"=>true);
		$reason = "";
		try {
			session_destroy();
			$result = array("isLogin"=>false);
		} catch(Exception $e){
			throw $e;
		}
		return $result;
	}

	
	public function checkToken($token){
		$result = false;
		try {
			$args = array("token"=>md5($token."bms"));
			$sql = "SELECT id FROM bms_user WHERE token=:token";
			$rows = SQLHandler::executeSQL($this->conn, 'post', $sql, $args);
			if(count($rows)>0){
				$result = true;
			}
		} catch(Exception $e) {
			throw $e;
		}
		return $result;
	}

}

?>
