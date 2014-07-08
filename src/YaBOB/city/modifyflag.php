<?php

class YaBOB_City_modifyFlag extends YaBOB_AMF{

	public $cmd = 'city.modifyFlag';
	public $data;
	
	public function _($flag){
		
		$this->data = array(
			'newFlag'=>$flag,
		);
		
		return $this->buildAMF((object) array('cmd'=>$this->cmd, 'data'=>$this->data, ));
	}
	
}