<?php
class AuthMiddleware extends \Slim\Middleware
{
    public function call()
    {
        $app = $this->app;
        $this->next->call();
        $url = $app->request->getResourceUri();
        if($url!="/api/user/unique"&&$url!="/api/user/login"&&($_SESSION['isLogin']==1)) {
        	$app->response->setBody(json_encode(array("role"=>"0")));
        } 
    }
}