<?php

class YaBOB_AMF {
	
	public function buildAMF($data)
	{

		$amf = NEW AMFObject();
		$amf->addBody($data);
		$serializer = NEW AMFSerializer();

		//var_dump($amf->_bodys[0]);
		$serializer->writeAmf3Object($amf->_bodys[0]);
		$data = $serializer->outBuffer;

		return $data;
	}

	public function destructAMF($data)
	{

		$amf = new AMFObject($data);
		$deserializer = new AMFDeserializer($amf->rawData);
		return (object)$deserializer->readAmf3Data();
	}

	public function AMFlength($data)
	{
		return pack('H*', sprintf("%08x", strlen($data)));
	}	

	public function AMFunpack($data)
	{
		return unpack('H*', sprintf("%08x", strlen($data)-4));
	}
}