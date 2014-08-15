<?php 
	$privileges = array();
	$metaStr = file_get_contents("../../meta/meta.json");
	if(preg_match('/^\xEF\xBB\xBF/', $metaStr)){
		$metaStr = substr($metaStr, 3);
	}
	
	$models = json_decode($metaStr, true);
	foreach($models as $model){
		$url = array("path"=>"/api/".$model["name"], "self"=>7, "group"=>7, "others"=>7);
		array_push($privileges, $url);
	}

	if(count($privileges)>0) {
		$apiPre = "../../docs/";
		$folder = $apiPre."privilege/";
		$file = $folder."privilege.json";
		createDir(dirname($file));
		if (file_exists($file) == false) {
			$fp=fopen($file, "w+");
			fwrite($fp, json_encode($privileges));
			fclose($fp);
		}
	}

	function createDir($path){
		if (!file_exists($path)){
			createDir(dirname($path));
			mkdir($path, 0777);
		}
	}
?>
