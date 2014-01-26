<?php

$string = file_get_contents("maplog.txt");
$map = json_decode($string,true); unset($string);

var_dump($map['data']['castles']);

//foreach($map['data']['castles'] as $castles)
//{	
//	//var_dump($castles);
//	$data['x'] = intval($castles['id'] % 800);
//	$data['y'] = intval($castles['id'] / 800);
//
//	echo $data['x'].",".$data['y']."\n";
//}