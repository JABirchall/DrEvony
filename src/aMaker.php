<?php
// Socket connection includes
include('Socket/Server.php');
include('Socket/Client.php');
// AMF Core library includes
require_once( 'amfphp/core/amf/app/Gateway.php');
require_once( AMFPHP_BASE . 'amf/io/AMFSerializer.php');
require_once( AMFPHP_BASE . 'amf/io/AMFDeserializer.php');
// YaBOB Class uncludes
require_once('YaBOB/AMF.php');
require_once('YaBOB/Login.php');
require_once('YaBOB/Register.php');
require_once('YaBOB/Handshake.php');
require_once('YaBOB/common/Createnewplayer.php');
require_once('config.php');
require_once('curl.php');

echo "[INFO] Fetching server infomation for {$server}".PHP_EOL;

$curl = NEW Curl;
$return = $curl->get("http://{$server}.evony.com/config.xml");
//var_dump($return);
$feed = NEW SimpleXMLElement($return);
$address = (String)$feed->server[0];
$port = (int)$feed->port;
echo "[INFO] Starting loop".PHP_EOL;
while(1){
	echo "[INFO] Creating random account".PHP_EOL;

	$UID = uniqid();
	$emailgen = "{$UID}@lol.com";
	$password = $UID;

	$s = NEW Socket\Client($address,$port);
	echo "[INFO] Connecting to {$address}:{$port}",PHP_EOL;

	$AMF = NEW YaBOB_AMF();
	$amfHandshake = NEW YaBOB_Handshake();
	$amfReg = NEW YaBOB_Register;
	$regInfo = $amfReg->_($emailgen, $password); unset($amfReg);
	$regData = $AMF->AMFlength($regInfo).$regInfo;
	echo "[INFO] Waiting for reply".PHP_EOL;

	$s->write($amfHandshake); unset($amfHandshake);
	$s->write($regData);
	$in = @$s->read();

	$in = substr($in, 4);
	$response = $AMF->destructAMF($in);

	echo "[INFO] Got reply!",PHP_EOL;

	if(!isset($response->data)){
		echo "[ERROR] Something has gone wrong, maybe finished".PHP_EOL;
		exit("[EXIT] Unexpected error");
	}

	if($response->data['errorMsg'] === "need create player"){
		echo "[INFO] Creating player";
		$createplayer = NEW YaBOB_Common_Createnewplayer();
		$player = $createplayer->_($UID, '','','','');
		$createplayer = $AMF->AMFlength($player).$player;
		$s->write($createplayer);
		$in = $s->read();

		while($in){
			$out = @$out.$in;
			$in = @$s->read();
			echo ".";
		}

		echo PHP_EOL;
		echo "[INFO] Got reply!",PHP_EOL;
		$in = substr($out, 4);
		$response = $AMF->destructAMF($in);
		//var_dump($response);

		if(isset($response) && @$response->data['errorMsg'] === "NEED CAPTCHA"){
			echo "[ERROR] CAPTCHA BITCH!".PHP_EOL;
			exit("[EXIT] YOU GOT CAPTCHA'D");
		} else if($response->cmd === "common.createNewPlayer" && @$response->data['msg'] === "success"){
			echo "[SUCCESS] Player created! Email: {$emailgen} Password: {$password} ";
			$x = intval($response->data['player']['castles'][0]['fieldId'] % 800);
			$y = intval($response->data['player']['castles'][0]['fieldId'] / 800);
			echo "Castle coords: {$x},{$y} PlayerID: {$response->data['player']['castles'][0]['id']}".PHP_EOL;
		}else if(isset($response) && $response->data['errorMsg'] === "All Valleys are already occupied, please choose another state. ") {
			echo "[INFO] Server full".PHP_EOL;
			exit("[EXIT] {$response->data['errorMsg']}");
		} else {
			exit("[EXIT] Unknown error: {$response->data['errorMsg']}");
		}
	}

	echo "[INFO] Closing connection".PHP_EOL;
	unset($s);unset($response);unset($in);unset($out);
	echo "[INFO] Connecting to {$address}:{$port}",PHP_EOL;

	$s = NEW Socket\Client($address,$port);
	$amfHandshake = NEW YaBOB_Handshake();
	$amfLogin = NEW YaBOB_Login();
	$loginInfo = $amfLogin->_($emailgen,$password); unset($amfLogin);
	$loginData = $AMF->AMFlength($loginInfo).$loginInfo;

	$s->write($amfHandshake); unset($amfHandshake);
	$s->write($loginData);
	echo "[INFO] Waiting for reply";
	$in = $s->read();

	while($in){
		$out = @$out.$in;
		$in = @$s->read();
		echo ".";
	}

	echo PHP_EOL;
	echo "[INFO] Got reply!",PHP_EOL;

	$out = substr($out, 4);
	$response = $AMF->destructAMF($out);

	if(@$response->data['msg'] === "login success"){
		echo "[SUCCESS] server returned: {$response->data['msg']}".PHP_EOL;
		echo "[INFO] Saving player data".PHP_EOL;
		$playerformat = "EMAIL: {$emailgen}, PASSWORD: {$password}, PLAYERNAME: {$UID}, COORD: {$x},{$y}".PHP_EOL;
		file_put_contents("accounts.txt", $playerformat, FILE_APPEND);
	}else if(@$response->data['errorMsg'] === "need create player"){
		echo "[WARNING] Player mite of failed to create!".PHP_EOL;
	} else {
		var_dump($response);
		exit("[EXIT] Server returned: {$response->data['errorMsg']}");
	}
	unset($s);unset($response);unset($in);unset($out);
}