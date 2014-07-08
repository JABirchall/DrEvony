<?php

class YaBOB_City_modifyName extends YaBOB_AMF{

	public $cmd = 'city.modifyCastleName';
	public $data;
	
	public function _($castleid,$name,$logo = "images/icon/cityLogo/citylogo_01.png"){
		
		$this->data = array(
			'castleId'=>$castleid,
			'logUrl'=>$logo,
			'name'=>$name,
		);
		
		return $this->buildAMF((object) array('cmd'=>$this->cmd, 'data'=>$this->data, ));
	}
	
}