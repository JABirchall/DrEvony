<?php

$yabob = new YaBOBB_Shell_Proxy();
 
$x = 0;
while(true) {
	$yabob->hasWrite() ? $yabob->write() : null ;
	$yabob->hasRead() ? $yabob->read() : null ;
	usleep(25000);
	if($x == 400){
		echo 'Memory Consumtion:' . (memory_get_usage(1)/1048576) . "MB \n";
		$x = 0;
	}
	$x++;
}


class YaBOBB_Shell_Proxy {
	
	public function hasWrite()
	{
		return 1;
	}
	
	public function write()
	{
		
	}
	
	public function hasRead()
	{
		return 1;
	}
	
	public function read()
	{
		
	}
	
	
}


