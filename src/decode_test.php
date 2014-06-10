<?php

/* This file will be use to decode Evony Packets for creating classes.
 * Out put should be in the form of array('cmd' => '', 'data' => array())
 */
require_once( 'amfphp/core/amf/app/Gateway.php');
require_once( AMFPHP_BASE . 'amf/io/AMFSerializer.php');
require_once( AMFPHP_BASE . 'amf/io/AMFDeserializer.php');

$packet = "0A 0B 01 09 64 61 74 61 0A 01 11 63 61 73 74 6C 65 49 64 04 C5 F6 6B 09 74 79 70 65 04 01 01 07 63 6D 64 06 25 71 75 65 73 74 2E 67 65 74 51 75 65 73 74 54 79 70 65 01 ";

$Data = pack("H*",str_replace(" ", "", $packet));

$amf = new AMFObject($Data);
$deserializer = new AMFDeserializer($amf->rawData);

var_dump((object)$deserializer->readAmf3Data());
