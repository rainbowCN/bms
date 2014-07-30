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
				$_p_sql = $_p_sql.",".$key;
				$_v_sql = $_v_sql.",:".$key;
			}
			$_p_sql = $_p_sql.",password";
			$_v_sql = $_v_sql.",123456";
			$sql = "INSERT INTO ".$_REQUEST['model']."(".substr($_p_sql,1).") VALUES (".substr($_v_sql,1).");";
			SQLHandler::executeSQL($this->conn, 'put', $sql, $_REQUEST['data']);
			$result = $this->conn->lastInsertId();
		} catch(Exception $e){
			throw $e;
		}
		return $result;
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
	
	/**
	 * 
	 * @throws Exception
	 * @return multitype:boolean
	 */
	public function checkToken($accessToken){
		$result = false;
		try {
			$token = $this->getToken($accessToken);
			if(cook($accessToken,$token->salt)===$token->token){
				$result = true;
			}
		} catch(Exception $e){
			throw $e;
		}
		return $result;
	}	
	
	private function getToken($accessToken){
		$result = array();
		try {
			$tokens = file_get_contents("./static/config_data/token.json");
			if(preg_match('/^\xEF\xBB\xBF/',$result)){
				$tokens=substr($tokens,3);
			}
			foreach(json_decode($tokens) as $token){
				if($token->accessToken===$at){
					$result = $token;
					break;
				}
			}
		} catch(Exception $e) {
			throw $e;
		}
		echo $result;
	}
	
	private function cook($accessToken, $salt) {
		$result = false;
		try {
			$result = md5($accessToken.$salt);
		} catch(Exception $e){
			throw $e;
		}
		return $result;
	}
}

?>
