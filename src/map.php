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
require_once('YaBOB/common/Createnewplayer.php');
//require_once('YaBOB/common/Privatechat.php');
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
	echo $response->data['errorMsg'];
	//exit;
}
//$s->read();
	//var_dump($response);
require_once('database.php');
$map = NEW YaBOB_Common_mapInfo();
$DB = NEW database('127.0.0.1', 'evomap', 'root', '');
$DB->query("DELETE FROM coord_info WHERE servers_id = :sid",
	array(':sid' => 365));
for($x = 1; $x <= 781; $x += 20)
{
	for($y = 1; $y <= 781; $y += 20)
	{
		$x2 = $x+20;
		$y2 = $y+20;
		echo "Fetching map data for Chunk [20x20] : (".$x.",".$y2.") to (".$x2.",".$y.") - ";
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

		if($out->cmd !== "common.mapInfoSimple"){
			$out = "";
			$in = $s->read();
			while($in){
				$out = @$out.$in;
				$in = @$s->read();
			}
			$out = substr($out, 4);
			$out = $AMF->destructAMF($out);
		}
		//var_dump($out->data['castles']);

		$i = 0;
		foreach($out->data['castles'] as $castles)
		{
			if($castles['npc'] === false){
				$castles['x'] = intval($castles['id'] % 800);
				$castles['y'] = intval($castles['id'] / 800);
				$castles['allianceName'] = (isset($castles['allianceName']))? $castles['allianceName']:'';
				$DB->query("INSERT INTO coord_info (ci_id, servers_id, x, y, city_name, lord_name, alliance, status, flag, honor, prestige, disposition) VALUES (NULL, :sid, :x, :y, :cityname, :lordname, :alliance, :status, :flag, :honor, :prestige, :disposition)",
					array(':sid' => 365,
						  ':x' => $castles['x'],
						  ':y' => $castles['y'],
						  ':cityname' => $castles['name'],
						  ':lordname' => $castles['userName'],
						  ':alliance' => $castles['allianceName'],
						  ':status' => $castles['state'],
						  ':flag' => $castles['flag'],
						  ':honor' => $castles['honor'],
						  ':prestige' => $castles['prestige'],
						  ':disposition' => 1
						  ));
				$i++;
			}
		}
		echo $i." Players added to database\n";

		//file_put_contents('maplog.txt',json_encode($out)."\n",FILE_APPEND);
	}
}

