<?php

class User extends ModelSessionImpl{
	
	protected $moduleName = 'user';

	public function __construct() {
		if(!isset($_SESSION['users'])) {
			$result = array();
			$total = 4;
			$data1= array("id"=>1, "name"=>"Tom", "gender"=>"male", "email"=>"tom@test.com", "password"=>"123456");
			$data2= array("id"=>2, "name"=>"Rose", "gender"=>"female", "email"=>"rose@test.com", "password"=>"123456");
			$data3= array("id"=>3, "name"=>"Jack", "gender"=>"male", "email"=>"jack@test.com", "password"=>"123456");
			$data4= array("id"=>4, "name"=>"Steve", "gender"=>"male", "email"=>"steve@test.com", "password"=>"123456");
			for($i=1; $i<=$total; $i++) {
				$temp="data".$i;
				array_push($result, $$temp);
			}
			$_SESSION['users'] = $result;
		}
	}
   
    public function setModuleName($value) {
        $this->moduleName = $value;
    }
	
    public function getModuleName(){
        return $this->moduleName;
    }
	
    public function unique($args) {
    	$result = array("unique"=>false);
    	try {
    		foreach($_SESSION['users'] as $key=>$value) {
				if($value['name']==$args['name']) {
					$result = array("unique"=>true);
				}
			}
    	} catch(Exception $e){
    		throw $e;
    	}
    	return $result;
    }
    
    public function login($args) {
    	$result = array("login"=>false);
    	try {
    		foreach($_SESSION['users'] as $key=>$value) {
    			if($value['name']==$args['name']&&$value['password']==$args['password']) {
    				$_SESSION['user'] = $value;
    				$result = array("login"=>true);
    			}
    		}
    	} catch(Exception $e){
    		throw $e;
    	}
    	return $result;
    }
    
    public function exec($model, $field, $method) {
    	try {
    		return $this->$method();
    	} catch(Exception $e){
    		throw $e;
    	}
    }
    
}