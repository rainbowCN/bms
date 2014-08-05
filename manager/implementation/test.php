<?php


$username = "admin";
$password = "123456";

$salt = md5($password.md5($username));
echo $salt;
$accessToken = md5($username.$password);
$token = md5($accessToken.$salt);

print_r('salt: '.$salt);

print_r('accessToken: '.$accessToken);
print_r('token: '.$token);


$at = "112106951fc78c8edf934a7717f0841a";

$result = file_get_contents("./static/config_data/token.json");
if(preg_match('/^\xEF\xBB\xBF/',$result)){
	$result=substr($result,3);
}
$result = json_decode($result);
foreach($result as $access){
	if($access->accessToken===$at){
		return $access;
		break;
	}
}