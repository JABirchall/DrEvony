<?php

class YaBOB_Castle_speedUp extends YaBOB_AMF{

	public $cmd = 'castle.speedUpBuildCommand';
	public $data;
	
	public function _($castleid,$positionid,$itemid){
		
		$this->data = array(
			'castleId'=>$castleid,
			'positionId'=>$positionid,
			'itemId'=>$itemid,
		);
		
		return $this->buildAMF((object) array('cmd'=>$this->cmd, 'data'=>$this->data, ));
	}
	
}