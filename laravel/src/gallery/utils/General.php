<?php
namespace utils;

class General {

	
	static function sendCurl ($curlOptions) {

		$ch = curl_init();
		foreach ($curlOptions as $curl_opt_param => $curl_opt_value) {
			curl_setopt($ch, constant($curl_opt_param), $curl_opt_value);
		}	
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;

	}
	

}