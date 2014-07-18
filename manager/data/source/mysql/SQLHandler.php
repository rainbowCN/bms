<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of sqlHandler
 *
 * @author rainbow
 */
class SQLHandler {
        /**
     * action for insert/update/delete 
     * @param type $sql
     * @param type $args 
     */
    public static function executeSQL($conn, $type, $sql, $args=null) {
    	$result = null;
        try{
            $stmt = $conn->prepare($sql);
            if(isset($args)) {
                foreach($args as $k=>$v){
                    $stmt->bindParam(":".$k, $v);
                    unset($v);
                }
            }
            if($type=='put'||$type=='delete') {
            	$result = $stmt->execute();
            } else { 
            	$stmt->setFetchMode(PDO::FETCH_ASSOC);
            	$stmt->execute();
            	$result = $stmt->fetchAll();
            } 
            return $result;
        } catch (PDOException $e) {
            throw $e;
        }

    }
}

?>
