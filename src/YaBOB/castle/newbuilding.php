<?php


class YaBOB_Castle_newBuilding extends YaBOB_AMF{

	public $cmd = 'castle.newBuilding';
	public $data;
	
	public function _($castleid,$positionid,$buildingtype){
		
		$this->data = array(
			'castleId'=>$castleid,
			'positionId'=>$positionid,
			'buildingType'=>$buildingtype,
		);
		
		return $this->buildAMF((object) array('cmd'=>$this->cmd, 'data'=>$this->data, ));
	}
	
}