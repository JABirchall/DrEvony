<?php

class YaBOB_Shop_Use_Goods extends YaBOB_AMF{

	public $cmd = 'shop.useGoods';
	public $data;
	
	public function _($castleid, $num, $itemid){
		
		$this->data = array(
			'castleId'=>$castleid,
			'num'=>$num,
			'itemId'=>$itemid,
		);
		
		return $this->buildAMF((object) array('cmd'=>$this->cmd, 'data'=>$this->data, ));
	}
	
}