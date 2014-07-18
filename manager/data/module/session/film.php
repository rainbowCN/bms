<?php

class Film extends ModelSessionImpl{
	
	protected $moduleName = 'film';

	public function __construct() {
		if(!isset($_SESSION['films'])) {
			$result = array();
			$total = 2;
			$sources = array();
			$data1= array("id"=>1, "name"=>urlencode("·Ç³ÏÎðÈÅ"), "date"=>urlencode("2013-12-12"), "img"=>"../img/film/sirendingzhi.jpg", "actors"=>urlencode("¸ðÓÅ£¬Êæä¿"), "info"=>"...", "url"=>"dianying263.com");
			$data2= array("id"=>17, "name"=>urlencode("ÎÞÈËÇø"), "date"=>urlencode("2014-01-01"), "img"=>"../img/film/wurenqu.jpg", "actors"=>urlencode("Ðìá¿"), "info"=>"...", "url"=>"dianying263.com");
			for($i=1; $i<=$total; $i++) {
				$temp="data".$i;
				array_push($result, $$temp);
			}
			$_SESSION['films'] = $result;
		}
	}	
   
    public function setModuleName($value) {
        $this->moduleName = $value;
    }
	
    public function getModuleName(){
        return $this->moduleName;
    }

    public function exec($model, $field, $method) {
    	try {
    		return $this->$method();
    	} catch(Exception $e){
    		throw $e;
    	}
    }
    
    
}