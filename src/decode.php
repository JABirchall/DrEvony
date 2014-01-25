<?php
require_once( 'amfphp/core/amf/app/Gateway.php');
require_once( AMFPHP_BASE . 'amf/io/AMFSerializer.php');
require_once( AMFPHP_BASE . 'amf/io/AMFDeserializer.php');
 
//$Data = pack("H*",str_replace(" ", "", "0A 0B 01 07 63 6D 64 06 25 67 61 6D 65 43 6C 69 65 6E 74 2E 76 65 72 73 69 6F 6E 09 64 61 74 61 06 13 30 39 31 31 30 33 5F 31 31 01"));
//
//$amf = new AMFObject($Data);
//
//$deserializer = new AMFDeserializer($amf->rawData);
//$amfD = $deserializer->readAmf3Data();

//var_dump($amfD);
//print_r($deserializer->storedObjects);
//
//$serializer = new AMFSerializer();
//$serializer->serialize($amf);
// 
//echo "Output: ".$serializer->outBuffer;
$amf = NEW AMFObject();

$amfdata = (object) [
				 'cmd' => 1,
				 'data' => [
				 			'cmd1' => 1,
				 			'cmd2' => 2
				 			]
				];
$amf->addBody($amfdata);


//$amf = NEW AMFObject("");				
//$amf->addBody($amfdata);

//var_dump($amf);

$serializer = NEW AMFSerializer();
$result = $serializer->writeAmf3Object($amf->_bodys[0]);
echo "Output: ".$serializer->outBuffer."\n"; // flush the binary data 

$amf = NEW AMFObject($serializer->outBuffer);

$deserializer = new AMFDeserializer($amf->rawData);

$amfD = (object) $deserializer->readAmf3Data();

var_dump($amfD);