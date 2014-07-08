<?php

class YaBOB_Castle_upgradeBuilding extends YaBOB_AMF{

	public $cmd = 'castle.upgradeBuilding';
	public $data;
	
	public function _($castleid,$positionid){
		
		$this->data = array(
			'castleId'=>$castleid,
			'positionId'=>$positionid,
		);
		
		return $this->buildAMF((object) array('cmd'=>$this->cmd, 'data'=>$this->data, ));
	}
	
}