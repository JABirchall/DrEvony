<?php

class YaBOB_Interior_modifyTax extends YaBOB_AMF{

	public $cmd = 'interior.modifyTaxRate';
	public $data;
	
	public function _($castleid,$tax = 20){
		
		$this->data = array(
			'castleId'=>$castleid,
			'tax'=>$tax,

		);
		
		return $this->buildAMF((object) array('cmd'=>$this->cmd, 'data'=>$this->data, ));
	}
	
}