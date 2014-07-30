<?php

/**
 * Auth
 *
 * @author James.Yu
 * @created 2014-07-30
 */
class ADCAuth extends \Slim\Middleware
{
    /**
     * @var array
     */
    protected $settings;

    /**
     * Constructor
     * @param array $settings
     */
    public function __construct($settings = array()){
        $this->settings = $settings;
    }

    public function call(){
		$isAuth = false;
		$accessToken = "";
		try {
			$app = $this->app;
			$url = $app->request->getResourceUri();
			$isAuth = $this->checkAccessToken($app);
			if(preg_match('/\/touch)\b/', $url) || $isAuth || isset($_SESSION["user"])){
				$this->next->call();
			 }else {
				$app->response->setStatus(403);
				return;
			}
		} catch(Exception $e) {
			throw $e;
    	}  		
    }
    
    private function checkAccessToken($app){
    	$accessToken =  $app->request->params('token');
    	$user = new User();
    	return $user->checkAccessToken($accessToken);
    }
}
