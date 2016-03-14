<?php
/**
 * Created by PhpStorm.
 * User: balwant
 * Date: 24/7/15
 * Time: 11:34 AM
 */
namespace App\utils;

use Illuminate\Support\Facades\Config;

class ElasticUtils{
    private $isSuccessful=null;

    public function __construct(){
        $this->isSuccessful=true;
    }

    /**
     * @param $url
     * @param $method
     * @param $qry
     * @return bool|mixed|null
     */
    public function executeElasticQry ($url, $method, $qry) {
        try{
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_PORT, 9200);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $qry);
            $result = curl_exec($ch);
            curl_close($ch);
            return $result;
        }catch(\Exception $e){
            $this->isSuccessful = false;
            return $this->isSuccessful;
        }


    }
    /**
     * @param $start
     * @param $size
     * @return bool|mixed|null
     */
    public function getProductVariantsViews($id) {
        $url = Config::get('database.elastic.elastic113.host').':'.Config::get('database.elastic.elastic113.port').'/'.Config::get('database.elastic.elastic113.index').'/'.Config::get('database.elastic.elastic113.type').'/_search';
        $json_doc = array( "fields" => [ "view_last30" ], "query" => array( "bool" => array( "must" => [ array( "term" => array( "id" => $id ) ) ], "must_not" => [], "should" => [] ) ) );
        $qry = json_encode($json_doc);
        $result = self::executeElasticQry($url,'POST',$qry);
        $response = json_decode($result, true);
        $response = $response['hits']['hits'][0]['fields']['view_last30'];
        return $response;
    }
    public function getProductsFromElastic($start,$size){
        $url = Config::get('database.elastic.elastic113.host').':'.Config::get('database.elastic.elastic113.port').'/'.Config::get('database.elastic.elastic113.index').'/'.Config::get('database.elastic.elastic113.type').'/_search';
        $json_doc = array( "sort" => [ array( "mob_noofviews" => array( "order" => "desc" ) ) ], "query" => array( "bool" => array( "must" => [ array( "term" => array( "individual_flag" => "yes" ) ) ], "must_not" => [], "should" => [] ) ) , "from" => $start, "size" => $size, "facets" => array( ) );
        $qry = json_encode($json_doc);
        $result = self::executeElasticQry($url,'POST',$qry);
        return $result;
    }

    /**
     * @param $prod_id
     * @return mixed
     */
    public function datalayerFeed($prod_id){
        $service_url = 'http://180.179.213.68:8099/datalayer_feed.php?pids='.$prod_id.'&cat_id=553';
        if(strlen($prod_id) == 4 || strlen($prod_id) == 5){
            $curl = curl_init($service_url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $curl_response = curl_exec($curl);
            if($curl_response === false) {
                $info = curl_getinfo($curl);
                curl_close($curl);
                die('error occured during curl exec. Additioanl info: ' . var_export($info));
            }
            else{
                curl_close($curl);
                $this->updateElasticGallery($prod_id);
            }
        }
        return $curl_response;
    }


    /**
     * @param $prod_id
     */
    public function updateElasticGallery($prod_id){
        $service_url = 'http://180.179.213.67/newmobiles/public_html/image_gallery/video_gallery.php?id='.$prod_id;
        if(strlen($prod_id) == 4 || strlen($prod_id) == 5){
            $curl = curl_init($service_url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $curl_response = curl_exec($curl);
            var_dump($curl_response);
            if($curl_response === false) {
                $info = curl_getinfo($curl);
                curl_close($curl);
                die('error occured during curl exec. Additioanl info: ' . var_export($info));
            }
            else{
                curl_close($curl);
            }
        }
    }

}