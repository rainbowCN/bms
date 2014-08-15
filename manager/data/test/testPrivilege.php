<?php
/**
 * The "role-list" & "privilege-list" are the properties of user to do auth-part-2. 
 * When $g_privileges changed(+/-), the $g_roles needs to be counted. if not, "+" maybe do some reqs blocking, "-" maybe create useless router.   
 * When $:role in $g_roles changed(+/-) , the privilege-list needs to be counted. effects as $g_privileges changing. 

 */

	/**
	 * create:c || mask:4
	 * update&delete:x || mask:2
	 * read:r || mask:1
	 * no opt:- || mask:0 
	 */
	// All privileges map.
	$g_privileges = array(); 
	$user = array("path"=>"/api/user", "u"=>7);
	$blog = array("path"=>"/api/blog", "u"=>7);
	$comment = array("path"=>"/api/comment", "u"=>7);
	
	array_push($g_privileges, $user);
	array_push($g_privileges, $blog);
	array_push($g_privileges, $comment);
	
	// Roles, super & admin & register & guest
	$g_roles = array();
	
	$super = array();
	$user["u"]=7;
	$blog["u"]=7;
	$comment["u"]=7;
	array_push($super, $user);
	array_push($super, $blog);
	array_push($super, $comment);
	$g_roles["super"] = $super;

	
	$admin = array();
	$user["u"]=5;
	$blog["u"]=7;
	$comment["u"]=7;
	array_push($admin, $user);
	array_push($admin, $blog);
	array_push($admin, $comment);
	$g_roles["admin"] = $admin;
	
	$register = array();
	$user["u"]=0;
	$blog["u"]=7;
	$comment["u"]=7;
	array_push($register, $user);
	array_push($register, $blog);
	array_push($register, $comment);
	$g_roles["register"] = $register;
	
	$guest = array();
	$user["u"]=0;
	$blog["u"]=5;
	$comment["u"]=1;
	array_push($guest, $user);
	array_push($guest, $blog);
	array_push($guest, $comment);	
	$g_roles["guest"] = $guest;
	
	
	$user1 = array("id"=>1, "privilege"=>"", "roles"=>array("super"));
	$user2 = array("id"=>2, "privilege"=>"", "roles"=>array("admin","register"));
	
	// Count personal privileges.
	// Map roles with all privileges.
	$_privileges = array();
	foreach($user2["roles"] as $key=>$role){
		$privileges = $g_roles[$role];
		foreach($privileges as $privilege){
			array_push($_privileges, $privilege);
		}
	}
	print_r($_privileges);
	
	// Reduce visual privileges.
	$user2_privileges = array();
	foreach($_privileges as $privilege){
		if(isset($user2_privileges[$privilege["path"]])){
			if($user2_privileges[$privilege["path"]] < $privilege["u"]) {
				$user2_privileges[$privilege["path"]] = $privilege["u"];
			}
		} else {
			$user2_privileges[$privilege["path"]] = $privilege["u"];			
		}		
	}
	print_r($user2_privileges);
	exit;
?>