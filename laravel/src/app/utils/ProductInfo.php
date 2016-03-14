<?php
/**
 * Created by PhpStorm.
 * User: patel
 * Date: 28/1/15
 * Time: 11:34 AM
 */

namespace App\utils;


class ProductInfo {
    public function __construct(){
    }
    public static function getBrandFromProId($id,$ptype){
        try{
            $url_track = 'http://180.179.213.68:8099/api.php?pid=#pro_id&ptype=#pro_type&type=getBasicData';
            $url_track = str_replace('#pro_id',$id,$url_track);
            $url_track = str_replace('#pro_type',$ptype,$url_track);
            $method='GET';
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url_track);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $result = curl_exec($ch);
            return $result;
        }catch (\Exception $e){
            return null;
        }
    }
}