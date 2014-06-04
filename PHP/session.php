<?php 

// Простые сессии

class Session{
	private $id;
	private $sid;
	private $userId;

	public $tableName;
	

	public function __construct(){
		$this->tableName=TABLE_PREF.'sessions';
		$this->userId=0;
	}

	public function init($connection){
		try{
			if(!$connection['COOKIE']['sid']){
				$connection = $this->createSession($connection);
			}else{
				$this->sid=$GLOBALS['DB']->real_escape_string($connection['COOKIE']['sid']);
				if($this->isSessionExist($this->sid)){
					$session=$this->getSessionById($this->sid);
					if($session['userId']>0){
						try{
							$connection['user']=$this->getUser($session['userId']);
							$this->userId=$session['userId'];
						}catch(AuthenticationException $e){
							throw $e;
						}
						
					}
				}else{
					$connection = $this->createSession($connection);
				}
			}
		}catch(ServerException $e){
			throw $e;				
		}
		return $connection;
	}

	public function isSessionExist($sid){
		$result=$GLOBALS['DB']->query("SELECT `id` FROM `".$this->tableName."` WHERE `sid`='".$sid."'");
		if($result && $result->num_rows>0){
			return true;
		}else{
			return false;
		}
		
	}

	public function getSessionById($sid){
		$result=$GLOBALS['DB']->query("SELECT * FROM `".$this->tableName."` WHERE `sid`='".$sid."'");
		if($result){
			return $result->fetch_assoc();
		}else{
			
		}
	}

	public function getUser($id){
		$user=(new User($id))->returnUser();
		return $user;
	}

	public function getGroup($id){}

	public function checkGroup($id){}	

	public function generateSid(){
		return uniqid().uniqid().uniqid();
	}

	public function createSession($connection){
		$this->sid=$this->generateSid();	
		try{
			if($this->saveSession()){
				$connection['COOKIE']['sid']=$this->sid;
				setcookie("sid", $this->sid, null, '/', null ,false, false);
			}
		}catch(ServerException $e){
			throw $e;				
		}
		return $connection;
	}

	public function saveSession(){
		if($this->isSessionExist($this->sid)){
			$result=$GLOBALS['DB']->query("DELETE FROM `".$this->tableName."` WHERE `sid`='".$this->sid."'");
		}		
		$result=$GLOBALS['DB']->query("INSERT INTO `".$this->tableName."`(`sid`,`userId`) VALUES ('".$this->sid."','".$this->userId."')");
		if($result){
			return true;
		}else{
			return false;
		}
		

	}

	public function setUser($id){
		$connection['user']=$this->getUser($session['userId']);
		$this->userId=$id;
		$this->saveSession();
	}

	public function unSetUser($sid){
		$result=$GLOBALS['DB']->query("DELETE FROM `".$this->tableName."` WHERE `sid`='".$sid."'");
	}
}
?>