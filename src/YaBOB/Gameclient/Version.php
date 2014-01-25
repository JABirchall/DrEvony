<?php

class YaBOB_Gameclient_Version extends YaBOB_AMF{

	public $cmd = 'gameClient.version';
	public $data;
	
	public function _($client_version){
		
		$this->data = $client_version;
		
		return $this->buildAMF((object) array('cmd'=>$this->cmd, 'data'=>$this->data, ));
	}
	
}