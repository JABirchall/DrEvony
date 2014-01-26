<?php

include('Socket/Server.php');
include('Socket/Client.php');
require_once( 'amfphp/core/amf/app/Gateway.php');
require_once( AMFPHP_BASE . 'amf/io/AMFSerializer.php');
require_once( AMFPHP_BASE . 'amf/io/AMFDeserializer.php');
require_once('YaBOB/AMF.php');
require_once('YaBOB/Login.php');
require_once('YaBOB/Handshake.php');
require_once('YaBOB/common/Mapinfo.php');
/* Account infomation */
$acc_email = "acc_email"; // Put your email address here
$acc_password = "acc_password"; // Put your password here


error_reporting(-1);
ini_set('display_errors','on');

$address = '216.66.6.139';
$port = 443;

$s = new Socket\Client($address,$port);

echo 'Writing to ',$address,':',$port,PHP_EOL;

$AMF = NEW YaBOB_AMF();
$amfHandshake = NEW YaBOB_Handshake();
$amfLogin = NEW YaBOB_Login();

$loginInfo = $amfLogin->_($acc_email,$acc_password); unset($amfLogin);
$loginData = $AMF->AMFlength($loginInfo).$loginInfo;

$s->write($amfHandshake); unset($amfHandshake);
$s->write($loginData);

echo 'Getting response!',PHP_EOL;
//$out = "";
//$out = $s->read();
//$out .= $s->read();
//$out .= $s->read();

$in = $s->read();
while($in){
	$out = @$out.$in;
	$in = @$s->read();
	//if (strpos($in, "\n") !== false) break;
}
//echo $out;
// Login for loop
//for($i = 1; $i <= 3; $i++)
//{
//	$in = $s->read();
//	//if (strpos($in, "\n") !== false) break;
//	$out .= $in;
//}


echo 'Got response!',PHP_EOL;
$out = substr($out, 4);

$response = $AMF->destructAMF($out);

if(@$response->data['msg'] === "login success") echo 'server returned: '.$response->data['msg']."\n";
	else { echo 'server returned: '.$response->data['errorMsg']."\n"; exit;}
	//var_dump($response);

$map = NEW YaBOB_Common_mapInfo();

for($x = 1; $x <= 781; $x += 20)
{
	for($y = 1; $y <= 781; $y += 20)
	{
		$x2 = $x+20;
		$y2 = $y+20;
		echo "Fetching map data for Chunk [20x20] : (".$x.",".$y2.") to(".$x2.",".$y.")\n"; //PlaceHolder
		$mapChunk = $map->_($x, $y2, $x2, $y);
		$mapData = $AMF->AMFlength($mapChunk).$mapChunk;
		$s->write($mapData);
		$in = $s->read();
		unset($out);
		while($in){
			$out = @$out.$in;
			$in = @$s->read();
		}
		//echo bin2hex($out)."\n";

		$out = substr($out, 4);
		$out = $AMF->destructAMF($out);

		if($out->cmd === "server.SystemInfoMsg"){
			$out = "";
			$in = $s->read();
			while($in){
				$out = @$out.$in;
				$in = @$s->read();
			}
			$out = substr($out, 4);
			$out = $AMF->destructAMF($out);
		}
		file_put_contents('maplog.txt',json_encode($out)."\n",FILE_APPEND);
	}
}

