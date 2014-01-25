<?php

class YaBOB_Login extends YaBOB_AMF{

	public $cmd = 'login';
	public $data;
	
	public function _($email, $pwd){
		
		$this->data = array(
			'user'=>$email,
			'pwd'=>sha1($pwd),
		);
		
		return $this->buildAMF((object) array('cmd'=>$this->cmd, 'data'=>$this->data, ));
	}
	
}