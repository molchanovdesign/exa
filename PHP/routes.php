<?php 

//Вырезка кода с You Samara Простой роутинг

//Примеры url:

//  /city/category/item
//  /city/category/
//  /city
//  /api/apiReq

// Где city - название города
// category - категория навостей (sport, soc, leisure, culture)
// item -  названия статьи латинницей

class Router{
	private $tableName;

	private $apiRoutes = array(		
		"create-event",
		"publ",
		"unpubl",
		"load-image",
		"increase-going",
		"rate",
		"getEventsForMap",
		"saveAddress",
		"getBrick",
		"getBalloon",
		"timeSet",
		"getTimeEvent",
		"getVkImage",
		'getState'
		)

	function __construct(){
		$this->tableName=TABLE_PREF.'routes';
	}

	public function getPath($connection){
		$path=$connection['path'];


		$connection['fullPath']='';
		foreach ($connection['path'] as  $value) {
			if($value!='')$connection['fullPath'].='/'.$value;
		}

		$connection['GET']=$_GET;
		$connection['POST']=$_POST;
		
		$typeArr=explode('.', $path[count($path)-1]);
		$type='';
		
		if(count($typeArr)>1){
			$type=$typeArr[count($typeArr)-1];
		}		
		if($type!=''){
			$connection['responseType']='FILE';
			if($type=='php'){
				die();
			}else{
				if($type=='jpg' ){
					$connection['fileType']='image/jpeg';					
				}
				if($type=='png'){
					$connection['fileType']='image/png';	
				}
				if($type=='svg'){
					$connection['fileType']='image/svg+xml';
				}
				if($type!='js' && $type!='jpg' && $type!='png'&& $type!='svg'){
					$connection['fileType']='text/'.$type;
				}
				if($type=='js') {
					$connection['fileType']='text/javascript';
				}
			}
		}else{

			$connection['fileType']='text/html';		

			if($connection['path'][0]!='api'){
				$connection['city']=$connection['path'][0];
				if(!$connection['path'][1] && !$connection['path'][2]){
					$connection['responseType']='VIEW';	
					$connection['locals']['template']='public/templates/index';
				}
				else if($connection['path'][1] && !$connection['path'][2]){
					$connection['responseType']='VIEW';	
					$connection['locals']['template']='public/templates/category';
				}
				else if($connection['path'][1] && $connection['path'][2]){
					if($connection['path'][1]=='advanced' && $connection['path'][2]=='map'){
						$connection['responseType']='VIEW';	
						$connection['locals']['template']='public/templates/map';
					}else{	
						$connection['responseType']='VIEW';	
						$connection['locals']['template']='public/templates/item';						
					}
				}
				else {
					throw new NotFoundException("Route not found");				
					die();
				}
			}
			if($connection['path'][0]=='api'){
				$found = false;
				foreach ($this->apiRoutes as $path) {
					if($connection['path'][1]==$path){
						$connection['responseType']='API';	
						$connection['locals']['script']='api/'.$path;
						$found = true;
						break;
					}
				}
				if(!$found){
					throw new NotFoundException("Route not found");				
					die();
				}
				
			}
		}
		return $connection;
	}	
}
?>