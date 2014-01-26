<?php

include('Socket/Server.php');
include('Socket/Client.php');
require_once( 'amfphp/core/amf/app/Gateway.php');
require_once( AMFPHP_BASE . 'amf/io/AMFSerializer.php');
require_once( AMFPHP_BASE . 'amf/io/AMFDeserializer.php');
require_once('YaBOB/AMF.php');
require_once('YaBOB/Login.php');
require_once('YaBOB/Handshake.php');

require_once('YaBOB/common/Createnewplayer.php');
require_once('YaBOB/common/Privatechat.php');
require_once('YaBOB/Mail/Sendmail.php');
require_once('config.php');



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


$in = $s->read();
while($in){
	$out = @$out.$in;
	$in = @$s->read();
	//if (strpos($in, "\n") !== false) break;
}



echo 'Got response!',PHP_EOL;
$out = substr($out, 4);

$response = $AMF->destructAMF($out);

if(@$response->data['msg'] === "login success")
	echo 'server returned: '.$response->data['msg']."\n";
else if(@$response->data['errorMsg'] === "need create player"){
	$createplayer = NEW YaBOB_Common_Createnewplayer();
	$player = $createplayer->_("Packet", '','','','');
	$createplayer = $AMF->AMFlength($player).$player;
	$s->write($createplayer);
	$in = $s->read();
	//var_dump($in);
} else {
	echo 'server returned: '.$response->data['errorMsg'];
	exit;
}
//$s->read();
	//var_dump($response);


function mathRandom () {
  return (float)rand()/(float)getrandmax();
}

$mail = NEW YaBOB_Mail_Sendmail();
$chat = NEW YaBOB_Common_Privatechat();
echo "Starting neatbot pause hash bruteforce\n";
$username = 'hasher';
$your_name = 'DrWhat';

for($i = 1; $i >= 0; $i++)
{
	//$message = hash_pbkdf2("crc32", mcrypt_create_iv(8), mcrypt_create_iv(8), 1, 8);
	$message = hash_pbkdf2("md5", md5("abcd".mathRandom()).$username." ".$your_name, '', 1, 8);

	$chatMessage = $chat->_($username,"//pause ".$message);
	$chatData = $AMF->AMFlength($chatMessage).$chatMessage;
	$s->write($chatData);
	@$s->read();
	checkmessage($message,@$s->read(),$username,$i);
}


function checkmessage($hash, $read, $username,$i){
	$out = substr($read, 4);
	$AMF1 = NEW YaBOB_AMF();
	$out = $AMF1->destructAMF($out);
	if(@$out->data['fromUser'] === $username){
		echo "[HASH FOUND]: ".$hash.", sent ".$i." Uniuqe hashes.\n";
		exit;
	}
}
//var_dump($out->data);
//echo "[HASH FOUND]: ".$message.", sent ".$i." Uniuqe hashes.";