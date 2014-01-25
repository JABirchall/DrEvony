<?php


class YaBOB_Common_Privatechat extends YaBOB_AMF{

	public $cmd = 'common.privateChat';
	public $data;
	
	public function _($targetName, $msg){
		
		$this->data = array(
			'msg'=>$msg,
			'targetName'=>$targetName,
		);
		
		return $this->buildAMF((object) array('cmd'=>$this->cmd, 'data'=>$this->data, ));
	}
	
}