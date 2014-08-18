<?php
/**
 * The "role-list" & "privilege-list" are the properties of user to do auth-part-2. 
 * When $g_privileges changed(+/-), the $g_roles needs to be counted. if not, "+" maybe do some reqs blocking, "-" maybe create useless router.   
 * When $:role in $g_roles changed(+/-) , the privilege-list needs to be counted. effects as $g_privileges changing. 

 */

class Privileger {
	
	private $g_sources = array();
	private $g_roles = array();
	/**
	 * create:c || mask:4
	 * update&delete:x || mask:2
	 * read:r || mask:1
	 * no opt:- || mask:0 
	 */
	// All resources & global privileges map.
	public function mockSources() {
		$user = array("path"=>"/api/user", "u"=>7);
		$blog = array("path"=>"/api/blog", "u"=>7);
		$comment = array("path"=>"/api/comment", "u"=>7);
		
		$this->g_sources["user"] = $user;
		$this->g_sources["blog"] = $blog;
		$this->g_sources["comment"] = $comment;
	} 


	// Roles, super & admin & register & guest
	public function mockPrivileges() {
		$super = array();
		$this->g_sources["user"]["u"]=7;
		$this->g_sources["blog"]["u"]=7;
		$this->g_sources["comment"]["u"]=7;
		array_push($super, $this->g_sources["user"]);
		array_push($super, $this->g_sources["blog"]);
		array_push($super, $this->g_sources["comment"]);
	
		$admin = array();
		$this->g_sources["user"]["u"]=5;
		$this->g_sources["blog"]["u"]=7;
		$this->g_sources["comment"]["u"]=7;
		array_push($admin, $this->g_sources["user"]);
		array_push($admin, $this->g_sources["blog"]);
		array_push($admin, $this->g_sources["comment"]);
	
		$register = array();
		$this->g_sources["user"]["u"]=0;
		$this->g_sources["blog"]["u"]=7;
		$this->g_sources["comment"]["u"]=7;
		array_push($register, $this->g_sources["user"]);
		array_push($register, $this->g_sources["blog"]);
		array_push($register, $this->g_sources["comment"]);
	
		$guest = array();
		$this->g_sources["user"]["u"]=0;
		$this->g_sources["blog"]["u"]=5;
		$this->g_sources["comment"]["u"]=1;
		array_push($guest, $this->g_sources["user"]);
		array_push($guest, $this->g_sources["blog"]);
		array_push($guest, $this->g_sources["comment"]);

		// All roles of app.
		$this->g_roles["super"] = $super;
		$this->g_roles["admin"] = $admin;
		$this->g_roles["register"] = $register;
		$this->g_roles["guest"] = $guest;
	}
	
	
	// Map roles with all privileges.
	function map($roles, $g_roles){
		$_privileges = array();
		foreach($roles as $key=>$role){		
			$privileges = $this->g_roles[$role];
			foreach($privileges as $privilege){
				array_push($_privileges, $privilege);
			}
		}
		return $_privileges;
	}
	
	// Reduce visual privileges.
	function reduce($privileges){
		$_privileges = array();
		foreach($privileges as $privilege){
			if(isset($_privileges[$privilege["path"]])){
				if($_privileges[$privilege["path"]] < $privilege["u"]) {
					$_privileges[$privilege["path"]] = $privilege["u"];
				}
			} else {
				$_privileges[$privilege["path"]] = $privilege["u"];
			}
		}
		return $_privileges;
	}
	
	// Test.
	function main() {
		$privileges = array();
		
		// Mock data
		$this->mockSources();
		$this->mockPrivileges();
		$user = array("id"=>2, "privilege"=>"", "roles"=>array("admin","register"));
		
		// Map & Reduce
		$privileges = $this->map($user["roles"], $this->g_roles);
		$privileges = $this->reduce($privileges);
		
		return $privileges;
	}

}

$privileger = new Privileger();
print_r($privileger->main());

