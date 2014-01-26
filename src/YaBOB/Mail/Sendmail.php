<?php


class YaBOB_Mail_Sendmail extends YaBOB_AMF{

	public $cmd = 'mail.sendMail';
	public $data;
	
	public function _($content,$username,$title){
		
		$this->data = array(
			'content'=>$content,
			'title'=>$title,
			'username'=>$username,

		);
		
		return $this->buildAMF((object) array('cmd'=>$this->cmd, 'data'=>$this->data, ));
	}
	
}