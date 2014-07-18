<?php


/**
 * The data accessing layer wraps FortiADCPHP extension supplys RESTful data webservice, it implements model interface and 
 * supplies 15 common implemented methods. If you have many special business methods, put into subclass is a good choice. 
 * @author James.Yu
 * @created 2013-07-12 
 * @updated 2013-09-09
 */
class ModelSessionImpl implements Model{

    public function create() {
    	$result = null;
    	try {
    		$_id = -1;
			foreach($_SESSION[$_REQUEST['model'].'s'] as $key=>$value) {
				$_id = ((int)$value['id']>$_id)?(int)$value['id']:$_id;	
			}
			$_REQUEST['data']['id'] = $_id+1;
			if($_REQUEST['model']=='user') {
				$_REQUEST['data']['password'] = '123456';
			}
			array_push($_SESSION[$_REQUEST['model'].'s'], $_REQUEST['data']);
			$result = $_REQUEST['data'];
    	} catch(Exception $e){
    		throw $e;
    	}
		return $result;
    }  
	
    public function remove($id) {
    	$result = array();    	
    	try {
    		foreach($_SESSION[$_REQUEST['model'].'s'] as $key=>$value) {
    			if($value['id']==$id){
    				unset($_SESSION[$_REQUEST['model'].'s'][$key]);
    				break;
    			}
    		}
    	} catch(Exception $e){
    		$result = array("error"=>$e->getMessage());
    	}
		return $result;	
    }       
	
	
    public function update($id) {
    	$result = null;
    	try {
    		foreach($_SESSION[$_REQUEST['model'].'s'] as $key=>$value) {
    			if($value['id']==$id) {
    				unset($_REQUEST['data']['_id']);
    				$_SESSION[$_REQUEST['model'].'s'][$key] = $_REQUEST['data'];    				
    			}
    			$result = $_SESSION[$_REQUEST['model'].'s'];
    		}    		
    	} catch(Exception $e){
    		throw $e;
    	}
		return $result;			
    }
	
	
    public function read($id) {
    	$result = array();    	
    	try {
    		foreach($_SESSION[$_REQUEST['model'].'s'] as $key=>$value) {
    			if($value['id']==$id){
    				$result = $value;
    				break;
    			}
    		}
    	} catch(Exception $e){
    		$result = array("error"=>$e->getMessage());
    	}
		return $result;	
	}
	

    public function all() {
    	$result = array();    	
    	try {
    		$result = $_SESSION[$_REQUEST['model'].'s'];
    	} catch(Exception $e){
    		$result = array("error"=>$e->getMessage());
    	}	
		return $result;				
    }  
	
}

