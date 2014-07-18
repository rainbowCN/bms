<?php
header("Content-Type:text/html;charset=utf-8");

session_start();

require '../../lib/Slim/Slim.php';
\Slim\Slim::registerAutoloader();
require '../data/core/classesautoloader.php';

$app = new \Slim\Slim();
$app->response->headers->set('Content-Type', 'application/json');
//$app->add(new AuthMiddleware());
$app->add(new FormatResponseMiddleware());

$app->config('prefix', "bms_");

// Utils
function getMillisecond() {
	list($s1, $s2) = explode(' ', microtime());
	return (float)sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000);
}

// [App/Get]
// domain/quit
$app->get('/logout', function() use ($app) {
	$result = array("isLogin"=>true);
	$reason = "";
	try {
		session_destroy();
		$result = array("isLogin"=>false);
		//$app->redirect('/index.html');
	} catch(Exception $e) {
		$app->flash('error', $e->getMessage());
	}
	echo json_encode($result);
});

// [App/Get] 
// domain/touch
$app->get('/touch', function() use ($app) {
	$result = array("isLogin"=>false);
	try {
		if (isset($_SESSION["isLogin"])&&($_SESSION["isLogin"])) {
			$result = array("isLogin"=>true, "user"=>$_SESSION["user"]);
		}
	} catch(Exception $e) {
		$app->flash('error', $e->getMessage());
	}
	echo json_encode($result);
});
	


$app->get('/menu', function() use ($app) {
	try {
		$result = array();
		$result = file_get_contents("./static/menu.json");
		if(preg_match('/^\xEF\xBB\xBF/',$result)){
			//$result=substr($result,3);
		}
		echo $result;
	} catch(Exception $e) {
		$app->flash('error', $e->getMessage());
	}
});

$app->post('/image', function() use ($app) {
	$result = "";
	$targetDir = "./upload/images/".getMillisecond();
	try {
		$file = array_pop($_FILES);
		if ($file["error"] > 0) {
			$result = "Error: ".$file["error"]."<br />";
		} else {
			$targetFile = $targetDir.str_replace(".", "", $file["name"]);
			move_uploaded_file($file["tmp_name"], $targetFile);
			$result = $targetFile;
		}
		echo json_encode(array("result"=>$result));
	} catch(Exception $e) {
		throw $e;
		$app->flash('error', $e->getMessage());
	}
});

$app->delete('/api/image/:id', function($id) use ($app) {
	$result = true;
	$targetFile = "./upload/images/".$id;
	try {
		unlink($targetFile);
	} catch(Exception $e) {
		$result = false;
	}
	echo json_encode(array("result"=>$result));
});
	
//[Normal] POST route
$app->post(
	'/api/:model',
	function ($model) use ($app) {
		$result = null;
		$_REQUEST['model'] = $app->config('prefix').$model;
		$body = $app->request->getBody();
		$_REQUEST['data'] = json_decode($body, true);
		$model = new $model();
		$result = $model->create();
		echo json_encode($result);
	}
);
//[Normal] DELETE route
$app->delete(
		'/api/:model/:id',
		function ($model, $id) use ($app) {
			$result = null;
			$_REQUEST['model'] = $app->config('prefix').$model;
			$model = new $model();
			$result = $model->remove($id);
			echo json_encode($result);
		}
);
//[Normal] PUT route
$app->put(
		'/api/:model/:id',
		function ($model, $id) use ($app) {
			$result = null;
			$_REQUEST['model'] = $app->config('prefix').$model;
			$body = $app->request->getBody();
			$_REQUEST['data'] = json_decode($body, true);
			$model = new $model();
			$result = $model->update($id);
			echo json_encode($result);
		}
);
//[Normal] Get all [/user]
$app->get(
		'/:model/',
		function ($model) use ($app) {
			$patch = parse_url($model);
			if(isset($patch['query'])) {
				parse_str($patch['query']);
				$_REQUEST['offset'] = $offset;
				$_REQUEST['size'] = $size;
			}
			$model = $patch['path'];
			$_REQUEST['model'] = $app->config('prefix').$model;
			$model = new $model();
			$result = $model->all();
			echo json_encode($result);
		}
);

//[Normal] Get one [/api/user/1]
$app->get(
		'/api/:model/:id',
		function ($model, $id) use ($app)  {
			$result = null;
			$_REQUEST['model'] = $app->config('prefix').$model;
			$model = new $model();
			$result = $model->read($id);
			echo json_encode($result);
		}
);

//[Logic] POST route [/api/user/login]
$app->post(
	'/api/:model/:method',
	function ($model, $method) use ($app) {
		$result = null;
		$body = $app->request->getBody();
		$options = json_decode($body, true);
		$model = new $model();
		$result = $model->$method($options);
		echo json_encode($result);
	}
);

$app->run();
