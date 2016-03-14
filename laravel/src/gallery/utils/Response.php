<?php
namespace classes;

class Response{

	static function JSON($data){
		return json_encode($data);
	}
   
}