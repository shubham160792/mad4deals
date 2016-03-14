<?php
namespace classes;

class Response{

	private static function setHttpHeader($httpCode){
		header("HTTP/1.0 ".$httpCode);
	}

	static function error($httpCode, $options){
		
		self::setHttpHeader($httpCode);
		echo json_encode($options);
	}

	static function JSON($httpCode, $data){
		
		self::setHttpHeader($httpCode);
		echo json_encode($data);
	}

	static function send($httpCode, $data){
		
		self::setHttpHeader($httpCode);
		echo $data;
	}
}