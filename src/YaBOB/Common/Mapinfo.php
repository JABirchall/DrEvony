<?php


class YaBOB_Common_mapInfo extends YaBOB_AMF{

	public $cmd = 'common.mapInfoSimple';
	public $data;
	
	public function _($x1,$y2,$x2,$y1){
		
		$this->data = array(
			'x1'=>$x1,
			'y2'=>$y2,
			'x2'=>$x2,
			'y1'=>$y1,
		);
		
		return $this->buildAMF((object) array('cmd'=>$this->cmd, 'data'=>$this->data, ));
	}
	
}