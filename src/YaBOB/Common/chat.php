<?php

class YaBOB_Common_chat extends YaBOB_AMF{

	public $cmd = 'common.channelChat';
	public $data;
	
	public function _($msg, $channel = 'beginner'){
		
		$this->data = array(
			'languageType'=>0,
			'channel'=>$channel,
			'sendMsg'=>$msg,
		);
		
		return $this->buildAMF((object) array('cmd'=>$this->cmd, 'data'=>$this->data, ));
	}
	
}