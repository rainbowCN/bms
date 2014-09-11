<?php
function sourceData($path, $suffix) {
	$handle = opendir($path);
	$itemArray = array();
	while (false !== ($file = readdir($handle))) {
		if (($file == '.') || ($file == '..')) {
		} elseif (is_dir($path . $file)) {
			try {
				$dirtmparr = sourceData($path . $file . '/', $suffix);
			} catch (Exception $e) {
				$dirtmparr = null;
			};
			array_push($itemArray, array('name'=>$file, 'children'=>$dirtmparr));
		} else {
			$_name = basename($file, $suffix);
			array_push($itemArray, array('name'=>$_name, 'path'=>$path.$_name));
		}
	}
	return $itemArray;
}
 $temp = sourceData("test/", ".txt");
 print_r($temp);