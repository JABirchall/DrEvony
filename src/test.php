<?php

/* This file will be use to decode Evony Packets for creating classes.
 * Out put should be in the form of array('cmd' => '', 'data' => array())
 */
require_once( 'amfphp/core/amf/app/Gateway.php');
require_once( AMFPHP_BASE . 'amf/io/AMFSerializer.php');
require_once( AMFPHP_BASE . 'amf/io/AMFDeserializer.php');

$Data = pack("H*",str_replace(" ", "", "0A 23 01 07 63 6D 64 09 64 61 74 61 06 29 73 65 72 76 65 72 2E 4C 6F 67 69 6E 52 65 73 70 6F 6E 73 65 0A 33 01 13 70 61 63 6B 61 67 65 49 64 05 6F 6B 11 65 72 72 6F 72 4D 73 67 05 00 00 00 00 00 00 00 00 04 FF FF FF FC 06 25 6E 65 65 64 20 63 72 65 61 74 65 20 70 6C 61 79 65 72 "));

$amf = new AMFObject($Data);
$deserializer = new AMFDeserializer($amf->rawData);

var_dump((object)$deserializer->readAmf3Data());
