<?php

class YaBOB_Shop_Buy extends YaBOB_AMF{

	public $cmd = 'shop.buy';
	public $data;
	
	public function _($amount, $item){
		
		$this->data = array(
			'amout'=>$amount,
			'itemId'=>$item,
		);
		
		return $this->buildAMF((object) array('cmd'=>$this->cmd, 'data'=>$this->data, ));
	}
	
}