<?php 
	$metaStr = file_get_contents("../../meta/meta.json");
	if(preg_match('/^\xEF\xBB\xBF/', $metaStr)){
		$metaStr = substr($metaStr, 3);
	}
	$models = json_decode($metaStr, true);
	
	foreach($models as $model){
		makeAPI($model);
	}
	
	function makeAPI($model){
		$apiPre = "../../docs/api/";
		$folder = $apiPre.(isset($model['path'])?$model['path']:$model['name'])."/";
		$file = $folder.$model['name'].".md";
		createDir(dirname($file));
		if (file_exists($file) == false) {
			$fp=fopen($file, "w+");
			fwrite($fp, $model['name']."\r\n======");
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