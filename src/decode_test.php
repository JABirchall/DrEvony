<?php

/* This file will be use to decode Evony Packets for creating classes.
 * Out put should be in the form of array('cmd' => '', 'data' => array())
 */
require_once( 'amfphp/core/amf/app/Gateway.php');
require_once( AMFPHP_BASE . 'amf/io/AMFSerializer.php');
require_once( AMFPHP_BASE . 'amf/io/AMFDeserializer.php');

$packet = "0A 0B 01 07 63 6D 64 06 25 63 6F 6D 6D 6F 6E 2E 70 72 69 76 61 74 65 43 68 61 74 09 64 61 74 61 0A 01 15 74 61 72 67 65 74 4E 61 6D 65 06 0B 4C 45 45 43 48 07 6D 73 67 06 07 48 45 59 01 01 ";

$Data = pack("H*",str_replace(" ", "", $packet));

$amf = new AMFObject($Data);
$deserializer = new AMFDeserializer($amf->rawData);

var_dump((object)$deserializer->readAmf3Data());
