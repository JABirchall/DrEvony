<?php

// code: http://devzone.zend.com/article/1086
include('out_class.php');
include('Socket/Server.php');
include('Amfphp/ClassLoader.php');
error_reporting(-1);

$a = [1 => 1];
$b = [1 => 1];


// Set time limit to indefinite execution
set_time_limit(0);

// Set the ip and port we will listen on
$address = '127.0.0.1';
$port = 9080;

echo 'Listening on ',$address,':',$port,PHP_EOL;

$s = new Socket\Server($address,$port);
$AMF = NEW Amfphp_Core_Amf_Deserializer();

$s->listen(function($a, $b, $input){
  $AMF = NEW Amfphp_Core_Amf_Deserializer();
  echo "received: ".$input, PHP_EOL;
  $ret_str = $AMF->deserialize($input);
  echo "returning: ".$ret_str, PHP_EOL;
  return $ret_str;

});
