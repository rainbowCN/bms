<?php
/**
 * Define top data accessing method
 * @author James.Yu
 * @created 2013-07-12
 * @updated 2013-07-16 
 */
interface Model {
	
	/**
	 * data/:model/* router map methods
	 */
    public function create();
        
    public function remove($key);
	  
    public function update($key);
	 
    public function read($key);
	
    public function all();

}


