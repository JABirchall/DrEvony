<?php

class YaBOB_Quest_Award extends YaBOB_AMF{

	public $cmd = 'quest.award';
	public $data;
	
	public function _($castleid, $questid){
		
		$this->data = array(
			'castleId'=>$castleid,
			'questId'=>$questid,
		);
		
		return $this->buildAMF((object) array('cmd'=>$this->cmd, 'data'=>$this->data, ));
	}
	
}