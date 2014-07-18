<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DBHandler
 *
 * @author rainbow
 */
class DBHandler {
	/**
	// remote config
    private $dsn = "mysql:host=localhost;dbname=bms";
    private $user = "root";
    private $password = "jdi2014";
	*/

	private $dsn = "mysql:host=localhost;dbname=bms";
	private $user = "root";
	private $password = "";	
    private $conn = null;
  	
    public function __construct() {
        try {
            $this->conn = new PDO($this->dsn, $this->user, $this->password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES'utf8';"));
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw $e;
        }
    }
    
    public function __destruct() {
        $this->conn = null;
    }    
    /**
     *
     * @return type 
     */
    public function getConn(){
        return $this->conn;
    }

}

?>
