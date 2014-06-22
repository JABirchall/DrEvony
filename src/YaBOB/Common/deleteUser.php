<?php

class YaBOB_Common_Delete_User extends YaBOB_AMF{

	public $cmd = 'common.deleteUserAndRestart';
	public $data;
	
	public function _($pwd){
		
		$this->data = array(
			'pwd'=>sha1($pwd),
		);
		
		return $this->buildAMF((object) array('cmd'=>$this->cmd, 'data'=>$this->data, ));
	}
	
}