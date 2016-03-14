<?php
/**
 * Created by PhpStorm.
 * User: boss
 * Date: 17/7/14
 * Time: 6:18 PM
 */

namespace utils;
use utils\Connection;

class ElasticSearchUtils {

    private $elasticSearchConnection = null;
    private $mysqlConnection = null;
    private $isSuccessful=null;
    private $newIndexName=null;
    private $search_query = '{
                              "query": {
                                "function_score": {
                                  "query": {
                                    "filtered": {
                                      "query": {
                                        "bool": {
                                          "must": [
                                          {
                                              "term": {
                                                "pro_id": "#pro_id"
                                              }
                                            },
                                            {
                                              "term": {
                                                "pro_cat_id": "#pro_cat_id"
                                              }
                                            }
                                            ],
                                          "must_not": [],
                                          "should": []
                                        }
                                      }
                                    }
                                  },
                                  "script_score": {
                                    "script": "doc.cat_bias.value"
                                  },
                                  "score_mode": "sum"
                                }
                              }
                            }';
    private $getMostPopular = '{
                                  "query": {
                                    "function_score": {
                                      "query": {
                                        "filtered": {
                                          "query": {
                                            "bool": {
                                              "must": [
                                                {
                                                  "query_string": {
                                                    "default_field": "gl.pro_brand",
                                                    "query": "#query"
                                                  }
                                                },
                                                {
                                                  "term": {
                                                    "pro_cat_id": "#pro_cat_id"
                                                  }
                                                }
                                              ],
                                              "must_not": [
                                                  {
                                                      "term": {
                                                        "pro_id": "#pro_id"
                                                      }
                                                    },
                                                    {
                                                      "term": {
                                                        "gl_cat_name": "videos"
                                                      }
                                                    }
                                                ],
                                              "should": []
                                            }
                                          }
                                        }
                                      },
                                      "script_score": {
                                        "script": "doc.product_views.value"
                                      },
                                      "score_mode": "sum"
                                    }
                                  },
                                  "size": #size
                                }';
    private $getProductIdsQuery = '{
                                      "query": {
                                        "bool": {
                                          "must": [
                                            {
                                              "query_string": {
                                                "default_field": "gl.gl_cat_name",
                                                "query": "#gl_category"
                                              }
                                            }
                                          ],
                                          "must_not": [],
                                          "should": []
                                        }
                                      },
                                      "fields": [
                                        "pro_id",
                                        "pro_name",
                                        "pro_cat_id"
                                      ],
                                      "from": 0,
                                      "size": 1000,
                                      "sort": [],
                                      "facets": {}
                                    }';

    private $album_query = '{
                              "query": {
                                "function_score": {
                                  "query": {
                                    "filtered": {
                                      "query": {
                                        "bool": {
                                          "must": [
                                            {
                                              "term": {
                                                "pro_id": "#pro_id"
                                              }
                                            },
                                            {
                                              "term": {
                                                "pro_cat_id": "#pro_cat_id"
                                              }
                                            },
                                            {
                                              "terms": {
                                                "gl_cat_name": ["#camera","#benchmark","#screen"]
                                              }
                                            }
                                            ],
                                          "must_not": [],
                                          "should": []
                                        }
                                      }
                                    }
                                  },
                                  "script_score": {
                                    "script": "doc.cat_bias.value"
                                  },
                                  "score_mode": "sum"
                                }
                              }
                            }';
    public function __construct(){
        $this -> conn = new Connection();
        $this->elasticSearchConnection = \Config::elasticIndexHost;
        $this -> mysqlConnection = $this -> conn -> getDbConnection();
        $this -> redis = $this -> conn -> getRedisConnection();
        $this->aliasName = \Config::ELASTIC_INDEX_NAME;
        $this->elasticType = \Config::ELASTIC_INDEX_TYPE;
        $this->isSuccessful=true;
        $this->newIndexName = $this->aliasName.'_'.time();
    }
    public function getCurrentIndexName(){
        return $this->newIndexName;
    }
    public function getAliasName(){
        return $this->aliasName;
    }
    public function switchToNewIndex($params){
        echo $url = $this->elasticSearchConnection.'_aliases';
        echo $method = 'POST';
        echo $qry = json_encode($params);
        $response = $this->executeElasticQry($url,$method,$qry);
        return $response;
    }

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
    public function createIndex(){
        $indexName = $this->getCurrentIndexName();
        $url = $this->elasticSearchConnection.$indexName;
        $method = 'PUT';
        $qry = '';
        $response = $this->executeElasticQry($url,$method,$qry);
        if($response == false){
            return $this->isSuccessful;
        }
        $newIndex = $this->getCurrentIndexName();
        try{
            $sql = 'insert into gl_elastics (index_name,date_created,is_successfully_created,is_deleted) VALUES (\''.$newIndex.'\',NOW(),0,0)';
            $query  = $this->mysqlConnection-> prepare( $sql );
            $query->execute();
            return $this->isSuccessful;
        }catch (\Exception $e){
            echo "some error while creating image gallery elastic index";
            $this->isSuccessful = false;
            return $this->isSuccessful;
        }
    }
    public function deleteIndex($previousIndexName) {
        $url = $this->elasticSearchConnection.$previousIndexName;
        $method = 'DELETE';
        $qry = '';
        $response = $this->executeElasticQry($url,$method,$qry);
        return $response;
    }
    public function insertData($id,array $body){
        $url = $this->elasticSearchConnection.$this->getCurrentIndexName().'/'.$this->elasticType.'/'.$id;
        $method = 'PUT';
        $qry = json_encode($body);
        $response = $this->executeElasticQry($url,$method,$qry);
        return $response;
    }
    public function insertSingleEntry($id,array $body){
        $url = $this->elasticSearchConnection.$this->getAliasName().'/'.$this->elasticType.'/'.$id;
        $method = 'PUT';
        $qry = json_encode($body);
        $response = $this->executeElasticQry($url,$method,$qry);
        return $response;
    }
    public function deleteElasticGallery($id) {
        $url = $this->elasticSearchConnection.$this->getAliasName().'/'.$this->elasticType.'/'.$id;
        $method = 'DELETE';
        $qry = '';
        $response = $this->executeElasticQry($url,$method,$qry);
        return $response;
    }
    public function deleteElasticGalleryByProductId($productId){
        $url = $this->elasticSearchConnection.$this->getAliasName().'/'.$this->elasticType.'/_query';
        $method = 'DELETE';
        $body = array(
            "query" => array(
                "bool" => array(
                    "must" => array(
                        array(
                            "term" => array(
                                "pro_id" => $productId
                            )
                        )
                    )
                )
            )
        );
        $qry = json_encode($body);
        $response = $this->executeElasticQry($url,$method,$qry);
        return $response;
    }
    public function updateData($id,array $body){

    }
    public function getElasticData($id,$catId){
        $qry =  str_replace('#pro_id',$id,$this->search_query);
        $qry =  str_replace('#pro_cat_id',$catId,$qry);
        $url = $this->elasticSearchConnection.$this->getAliasName().'/'.$this->elasticType.'/_search';
        $method = 'POST';

        $response = $this->executeElasticQry($url,$method,$qry);
        return $response;
    }
    public function getAlbumsData($id,$catId){
        $qry =  str_replace('#pro_id',$id,$this->album_query);
        $qry =  str_replace('#pro_cat_id',$catId,$qry);
        $qry =  str_replace('#camera',\Config::PARTIAL_CAMERA_CATEGORY,$qry);
        $qry =  str_replace('#benchmark',\Config::SCREEN_SHOTS_CATEGORY,$qry);
        $qry =  str_replace('#screen',\Config::BENCHMARK_CATEGORY,$qry);
        $url = $this->elasticSearchConnection.$this->getAliasName().'/'.$this->elasticType.'/_search';
        $method = 'POST';
        $response = $this->executeElasticQry($url,$method,$qry);
        return $response;
    }
    public function getMostPopularProduct($brand,$id,$catId){
        $qry =  str_replace('#query',$brand,$this->getMostPopular);
        $qry = str_replace('#pro_id',$id,$qry);
        $qry =  str_replace('#pro_cat_id',$catId,$qry);
        $qry = str_replace('#size',1,$qry);
        $url = $this->elasticSearchConnection.$this->getAliasName().'/'.$this->elasticType.'/_search';
        $method = 'POST';
        $response = $this->executeElasticQry($url,$method,$qry);
        return $response;
    }
    public function getDataFromRedis($id,$catId){
        $data = $this -> redis -> hGetAll('91m:h:product_attributes:'.$id.':'.$catId);
        return $data;
    }
    public function getProductIdsFromElastic($gl_category){
        $qry =  str_replace('#gl_category',$gl_category,$this->getProductIdsQuery);
        $url = $this->elasticSearchConnection.$this->getAliasName().'/'.$this->elasticType.'/_search';
        $method = 'POST';
        $response = $this->executeElasticQry($url,$method,$qry);
        $response = json_decode($response,true);
        $data = array();
        $response = $response['hits']['hits'];
        for($i=0;$i<count($response);$i++){
            $data[] = array('pro_id'=>$response[$i]['fields']['pro_id'][0],'pro_name'=>$response[$i]['fields']['pro_name'][0],'cat_id'=>$response[$i]['fields']['pro_cat_id'][0]) ;
        }
        return $data;
    }
    public function getDisplayTextFromCategory($catName){
        switch(strtolower($catName)){
            case \Config::CAMERA_SAMPLES_CATEGORY:
                return "Camera UI & Samples";
                break;
            case \Config::SCREEN_SHOTS_CATEGORY:
                return "UI Screenshots";
                break;
            case \Config::BENCHMARK_CATEGORY:
                return "Benchmarks";
                break;
            case \Config::DESIGN_CATEGORY:
                return "Design";
                break;
            case strtolower(\Config::SLIDE_SHARE_CATEGORY):
                return "Review in Pictures";
                break;
            default:
                return $catName;
        }
    }
}