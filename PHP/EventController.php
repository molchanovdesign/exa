<?php 

// Пример контроллера с Meet You

class EventController implements IBaseEventController{
	private $connection;

	function __construct($connection){
		$this->connection=$connection;
	}

	public function getEventById($id){
		return (new Event($id))->getFullInfo();
	}

	public function getEventList($connection, $storage){
		try{
			$colB=new IndexColBuilder();
			return $storage->getEventList($connection, $colB);
		}catch(EventException $e){
			if(DEBUG_MODE){
				echo $e->getMessage();
			}else{
				echo "Не удалось загрузить события =(";
			}
			die();
		}		
	}

	public function getCatList($connection, $storage){		
		try{
			$cl=$storage->getCatList($connection['path']);
			$cl['grey']=$connection['locals']['grey'];
			return $cl;
		}catch(EventException $e){
			if(DEBUG_MODE){
				echo $e->getMessage();
			}else{
				echo "Не удалось загрузить категории =(";
			}
			die();
		}	
	}

	public function getAdvList($path, $storage){
		try{
			return $storage->getAdvList($path);
		}catch(EventException $e){
			if(DEBUG_MODE){
				echo $e->getMessage();
			}else{
				
			}
			die();
		}	
	}

	public function getView($data, $type){
		switch ($type) {
			case 'MAIN_TOP':
			return new MainTopView($data);
			break;
			
			case 'ADV':
			$html=new InlineHTML($data);
			$info=$html->getInfo();
			return new InlineHTMLView($info);
			break;

			case 'COMMENTS':
			return new CommentsView(null);
			break;

			case 'BOTTOM':
			return new BottomView($data);
			break;			
		}
	}


	public function getEventView($eventId, $type){
		try{
			$event=new Event($eventId);
			switch ($type) {
				case 'MAIN_EVENT':
				$liteInfo=$event->getLiteInfo();
				$liteInfo['scroll']=0;
				return new MainEventLiteView($liteInfo);
				break;

				case 'FULL':
				$event->increaseHits();
				$info=$event->getFullInfo();

				if($this->connection['user'])$info['user']=$this->connection['user'];
				return new EventNewView($info);
				break;

				case 'BRICK':
				$info=$event->getFullInfo();
				return new BrickView($info);
				break;

				case 'BALLOON':
				$info=$event->getFullInfo();
				return new BalloonView($info);
				break;

				case 'BRICK_HOR':
				$info=$event->getFullInfo();
				return new BrickHorView($info);
				break;

			}
		}catch(EventException $e){
			if(DEBUG_MODE){
				echo $e->getMessage();
			}else{
				echo "Не удалось подгрузить событие =(";
			}
			die();
		}
	}

	public function getEventCountersView($eventId){
		try{
			$event=new Event($eventId);
		}catch(EventException $e){
			if(DEBUG_MODE){
				echo $e->getMessage();
			}else{
				echo "Не удалось подгрузить событие =(";
			}
			die();
		}
		return new CountersView($event->getCounters()->getCountersArray());
	}
}
?>