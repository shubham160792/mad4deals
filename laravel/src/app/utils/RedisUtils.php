<?php
/**
 * Created by PhpStorm.
 * User: balwant
 * Date: 23/9/15
 * Time: 5:08 PM
 */
namespace App\utils;

use Illuminate\Support\Facades\Config;
use App\constants\ItemType;

class RedisUtils{

    private $conn = null;
    private $redisHost = null;
    private $redisPort = null;

    public function __construct () {
        $this->redisHost = Config::get('database.redis.default.host');
        $this->redisPort = Config::get('database.redis.default.port');
        $this->conn = $this->redis_connect($this->redisHost, $this->redisPort);
    }
    public function redis_connect($host, $port){
        $redis = new \Redis();
        $redis->connect($host,$port);
        return $redis;
    }
    public function insertIntoRedis($prod_id, $category_id, $type, $data) {
        if($type == ItemType::$SlideShareType) {
            foreach ($data as $key => $value) {
                $this->conn->hset("91m:h:product_attributes:".$prod_id.":".$category_id, $key, $value);
            }
        }
        elseif ($type == ItemType::$YoutubeType) {
            foreach ($data as $key => $value) {
                $this->conn->hset("91m:h:product_attributes:".$prod_id.":".$category_id, $key, $value);
            }
        }

    }
    public function deleteIntoRedis($prod_id, $category_id, $type, $title) {
        if($type == ItemType::$SlideShareType) {
            if ($this->conn->hget("91m:h:product_attributes:" . $prod_id . ":" . $category_id, $title) != NULL) {
                    $this->conn->hdel("91m:h:product_attributes:" . $prod_id . ":" . $category_id, $title);
                    $this->conn->hdel("91m:h:product_attributes:" . $prod_id . ":" . $category_id, 'slideshare_status');
            }
        }
        elseif ($type == ItemType::$YoutubeType) {
            if ($this->conn->hget("91m:h:product_attributes:" . $prod_id . ":" . $category_id, $title) != NULL) {
                $this->conn->hdel("91m:h:product_attributes:".$prod_id.":".$category_id, $title);
                $title = explode("_", $title);
                $this->conn->hdel("91m:h:product_attributes:" . $prod_id . ":" . $category_id, $title[0].'_url');
                $this->conn->hdel("91m:h:product_attributes:" . $prod_id . ":" . $category_id, $title[0].'_status');
            }
        }

    }

    public function deleteCompleteHash($prod_id, $category_id, $type) {
        if($type == ItemType::$SlideShareType) {
            $this->conn->hdel("91m:h:product_attributes:" . $prod_id . ":" . $category_id, "slideshare_url");
            $this->conn->hdel("91m:h:product_attributes:" . $prod_id . ":" . $category_id, 'slideshare_status');
        }
        elseif ($type == ItemType::$YoutubeType) {
            $data = $this->conn->hGetAll("91m:h:product_attributes:".$prod_id.":".$category_id);
            foreach ($data as $key => $value) {
                if (stripos($key, "video") !== FALSE) {
                    if ((stripos($key, "_title") !== FALSE) || (stripos($key, "_url") !== FALSE) || (stripos($key, "_status") !== FALSE)) {
                        echo $key;
                        $this->conn->hdel("91m:h:product_attributes:" . $prod_id . ":" . $category_id, $key);
                    }
                }
            }

        }
    }

}