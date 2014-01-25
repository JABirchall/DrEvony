<?php
require_once( 'amfphp/core/amf/app/Gateway.php');
require_once( AMFPHP_BASE . 'amf/io/AMFSerializer.php');
require_once( AMFPHP_BASE . 'amf/io/AMFDeserializer.php');
require_once('YaBOB/AMF.php');

//$amf = NEW AMFObject();

$amfdata = (object) [
				 'cmd' => 1,
				 'data' => [
				 			'cmd1' => "this is a string",
				 			'cmd2' => (double)2
				 			]
				];
//$amf->addBody($amfdata);


//$amf = NEW AMFObject("");				
//$amf->addBody($amfdata);

//var_dump($amf);

//$serializer = NEW AMFSerializer();
//$result = $serializer->writeAmf3Object($amf->_bodys[0]);
//echo "Output: ".bin2hex($serializer->outBuffer)."\n\nAttempting to deserialize\n\n"; // flush the binary data 
//
//$amf = NEW AMFObject($serializer->outBuffer);
//
//$deserializer = new AMFDeserializer($amf->rawData);
//
//var_dump($amfD = (object)$deserializer->readAmf3Data());


$AMF = NEW YaBOB_AMF();

$rawData = $AMF->buildAMF($amfdata);
$pLength = $AMF->AMFlength($rawData);

echo "Encoded Packet: ".bin2hex($pLength.$rawData)."\n\nDecoded Packet:\n";
var_dump($AMF->destructAMF($rawData));