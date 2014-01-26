<?php

/* This file will be use to decode Evony Packets for creating classes.
 * Out put should be in the form of array('cmd' => '', 'data' => array())
 */
require_once( 'amfphp/core/amf/app/Gateway.php');
require_once( AMFPHP_BASE . 'amf/io/AMFSerializer.php');
require_once( AMFPHP_BASE . 'amf/io/AMFDeserializer.php');

$packet = "0A 23 01 07 63 6D 64 09 64 61 74 61 06 33 73 65 72 76 65 72 2E 50 72 69 76 61 74 65 43 68 61 74 4D 65 73 73 61 67 65 0A 43 01 13 6F 77 6E 69 74 65 6D 69 64 11 63 68 61 74 54 79 70 65 11 66 72 6F 6D 55 73 65 72 07 6D 73 67 04 00 04 00 06 0D 68 61 73 68 65 72 06 25 70 61 75 73 69 6E 67 20 66 6F 72 20 31 20 68 6F 75 72 ";

$Data = pack("H*",str_replace(" ", "", $packet));

$amf = new AMFObject($Data);
$deserializer = new AMFDeserializer($amf->rawData);

var_dump((object)$deserializer->readAmf3Data());
