<?php

class YaBOB_Common_Createnewplayer extends YaBOB_AMF{

	public $cmd = 'common.createNewPlayer';
	public $data;
	
	public function _($king, $castleName, $flag, $sex, $zone, $capcha=''){

		if(!$castleName){
			$castleName = 'City Name';
		}
		
		if(!$flag){
			$flag = 'Flag';
		}
		
		if(!$sex){
			$sex = '0';
		}else{
			$sex = '1';
		}
		
		if(!$zone){
			$zone = '0';
		}
		
		$this->data = array(
			'faceUrl' => $this->getFace($sex),
		    'zone' => (int)$zone,
		    'flag' => $flag,
		    'castleName' => $castleName,
		    'userName' => $king,
		    'captcha' => $capcha,
		    'sex' => (int)$sex,
		);
		
		return $this->buildAMF((object) array('cmd'=>$this->cmd, 'data'=>$this->data ));
	}
	
	public function getFace($s){
		if((int)$s){
			return 'images/icon/player/faceB8.jpg';
		}else{
			return 'images/icon/player/faceA8.jpg';
		}
	}
	
}