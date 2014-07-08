<?php

class YaBOB_Interior_modifyProduction extends YaBOB_AMF{

	public $cmd = 'interior.modifyCommenceRate';
	public $data;
	
	public function _($castleid,$food = 100,$wood = 100,$stone = 100,$iron = 100){
		
		$this->data = array(
			'castleId'=>$castleid,
			'foodrate'=>$food,
			'woodrate'=>$wood,
			'stonerate'=>$stone,
			'ironrate'=>$iron,
		);
		
		return $this->buildAMF((object) array('cmd'=>$this->cmd, 'data'=>$this->data, ));
	}
	
}