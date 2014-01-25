<?php

include('Socket/Server.php');
include('Socket/Client.php');
require_once( 'amfphp/core/amf/app/Gateway.php');
require_once( AMFPHP_BASE . 'amf/io/AMFSerializer.php');
require_once( AMFPHP_BASE . 'amf/io/AMFDeserializer.php');
require_once('YaBOB/AMF.php');
require_once('YaBOB/Login.php');
require_once('YaBOB/Handshake.php');

error_reporting(-1);
ini_set('display_errors','on');

$address = '216.66.6.139';
$port = 443;

$s = new Socket\Client($address,$port);

echo 'Writing to ',$address,':',$port,PHP_EOL;

$AMF = NEW YaBOB_AMF();
$amfHandshake = NEW YaBOB_Handshake();
$amfLogin = NEW YaBOB_Login();
$loginInfo = $amfLogin->_("youremail","yourpassword"); unset($amfLogin);
$loginData = $AMF->AMFlength($loginInfo).$loginInfo;

@$s->write($amfHandshake); unset($amfHandshake);
$s->write($loginData);

echo 'Getting response!',PHP_EOL;

while($out = $s->read()){
	$out .= @$s->read();
	if (strpos($out, "\n") !== false) break;
}

$out = substr($out, 4);

$response = $AMF->destructAMF($out);
if(@$response->data['msg'] === "login success") echo 'server returned: '.$response->data['msg'];
	else echo 'server returned: '.$response->data['errorMsg'];

for($x = 0; $x <= 800; $x += 20)
{
	for($y = 0; $y <= 800; $y += 20)
	{
		//echo "Fetching map data for Chunk [20x20] : (".$x.",".$y.")\n"; //PlaceHolder
	}
}

