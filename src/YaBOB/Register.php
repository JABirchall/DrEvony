<?php

class YaBOB_Register extends YaBOB_AMF{

	public $cmd = 'register';
	public $data;
	
	public function _($email, $pwd){
		
		$this->data = array(
			'user'=>$email,
			'pwd'=>sha1($pwd) . '=' . md5($pwd),
		);
		
		return $this->buildAMF((object) array('cmd'=>$this->cmd, 'data'=>$this->data, ));
	}
	
}