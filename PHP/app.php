<?php 

//запуск приложения

//index.php :

//include 'configs/main.php';
//$app=new App();
//$app->run();

//=========

//app.php :

class App{
	private $mainController;
	private $request;

	function __construct() {
		$GLOBALS['DB']=new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME); 
		if($GLOBALS['DB']->connect_errno){			
			if(DEBUG_MODE){
				echo $GLOBALS['DB']->error;	
				echo "db connection error";
			}else{
				echo "Серверная ошибка =(";
			}
			http_response_code(500);
			die();
		}

		$GLOBALS['DB']->set_charset("utf8");
		$GLOBALS['DB_NEWS']->set_charset("utf8");

		$this->request=$this->urlParser();
		$this->mainController = new MainController();
		$this->init();	
	}

	private function init() {
		if(DEBUG_MODE){
			error_reporting(E_ALL);
			ini_set('display_errors', 1);
		}else{
			ini_set('display_errors', 0);
		}
	}

	private function urlParser() {
		$url=parse_url('//'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
		$url['path']=substr($url['path'],1);
		$explodedPath= explode('/', $url['path']);
		$ep=[];
		foreach ($explodedPath as $value) {
			if($value!='')$ep[]=$value;
		}
		$url['path']=$ep;
		
		return $url;
	}

	public function run() {
		$this->mainController->getPageView($this->request);		
	}
}
?>