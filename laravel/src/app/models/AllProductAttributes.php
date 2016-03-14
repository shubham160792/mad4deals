<?php
/**
 * Created by PhpStorm.
 * User: balwant
 * Date: 28/7/15
 * Time: 10:58 AM
 */
//namespace models;
use App\response\ProductAttr;
use App\response\ContentSummary;
use App\constants\AttributeConstants;
use App\utils\RedisUtils;
use App\utils\ElasticUtils;
use App\constants\ItemType;

class AllProductAttributes extends Eloquent{


    public function _construct() {

    }


    /**
     * @param $start
     * @param $batchSize
     * @param int $isAll
     * @return \App\response\ProductAttr[]
     */
    public function getProductIds($start, $batchSize, $isAll=0) {

        /** @var \App\response\ProductAttr[] $result */
        $result = array();
        try {
            if($isAll == 0) {
                $res = DB::connection('mysql111')->select("SELECT DISTINCT product_id as id,product_name as name FROM product_attributes WHERE (attr_key IN('".implode("','",AttributeConstants::$PROD_ATTR_ARRAY)."') OR attr_key LIKE '".AttributeConstants::VIDEO."%') ORDER BY product_id ASC LIMIT " . $start . "," . $batchSize);
            } else {
                $res = DB::connection('mysql111')->select("SELECT DISTINCT product_id as id,product_name as name FROM product_attributes WHERE (attr_key IN('".implode("','",AttributeConstants::$PROD_ATTR_ARRAY)."') OR attr_key LIKE '".AttributeConstants::VIDEO."%') ORDER BY product_id ASC");
            }
            foreach ($res as $products) {
                $productAttr = $this->getProductAttr($products->id);
                $productAttr->id = $products->id;
                $productAttr->name = $products->name;
                $result[] = $productAttr;
            }
        } catch (\Exception $e) {}
        return $result;
    }
    /**
     * @param $id
     * @return ProductAttr
     */
    public function getProductAttr($id,$lastweek = false) {
        try {
            $objProdAttr = new ProductAttr();
            $res = DB::connection('mysql111')->select("SELECT attr_key,MIN(add_date) as add_date,MAX(edit_date) as edit_date FROM product_attributes WHERE (attr_key IN('".implode("','",AttributeConstants::$PROD_ATTR_ARRAY)."') OR attr_key LIKE '".AttributeConstants::VIDEO."%') AND product_id=" . $id . " GROUP BY attr_key");
            if($lastweek) {
                $startDate = date("Y-m-d", strtotime(AttributeConstants::MONDAY_LAST_WEEK));
                $endDate = date("Y-m-d", strtotime(AttributeConstants::SUNDAY_LAST_WEEK));
                $res = DB::connection('mysql111')->select("SELECT attr_key,MIN(add_date) as add_date,MAX(edit_date) as edit_date FROM product_attributes WHERE (attr_key IN('".implode("','",AttributeConstants::$PROD_ATTR_ARRAY)."') OR attr_key LIKE '".AttributeConstants::VIDEO."%') AND product_id=" . $id . " AND add_date BETWEEN '".$startDate."' AND '".$endDate."' GROUP BY attr_key");
            }
            foreach ($res as $row) {
                if ($row->attr_key == AttributeConstants::BENCHMARKS) {
                    $objProdAttr->benchMark = true;
                } elseif ($row->attr_key == AttributeConstants::CAMERA_SAMPLES) {
                    $objProdAttr->camera = true;
                } elseif ($row->attr_key == AttributeConstants::SCREENSHOTS) {
                    $objProdAttr->screenShot = true;
                } elseif ($row->attr_key == AttributeConstants::VIEW360_IMAGE_COUNT) {
                    $objProdAttr->views360 = true;
                } elseif ($row->attr_key == AttributeConstants::SLIDESHARE_REVIEW_URL) {
                    $objProdAttr->slideShare = true;
                } elseif(stripos($row->attr_key, AttributeConstants::VIDEO) !== FALSE) {
                    $objProdAttr->video = true;
                }
                $objProdAttr->add_date = (null == $objProdAttr->add_date || (strtotime($row->add_date) - strtotime($objProdAttr->add_date) < 0)) ? $row->add_date : $objProdAttr->add_date;
                $objProdAttr->edit_date = (null == $objProdAttr->edit_date || (strtotime($row->edit_date) - strtotime($objProdAttr->edit_date) > 0)) ? $row->edit_date : $objProdAttr->edit_date;

            }

            return $objProdAttr;
        }catch (\Exception $e) {

        }

    }

    /**
     * @return ContentSummary
     */
    public function getLastWeekRecords(){
        try {
            $startDate = date("Y-m-d", strtotime(AttributeConstants::MONDAY_LAST_WEEK));
            $endDate = date("Y-m-d", strtotime(AttributeConstants::SUNDAY_LAST_WEEK));
            /*echo "SELECT COUNT(DISTINCT product_id) AS total,attr_key FROM product_attributes WHERE (attr_key IN('".implode("','",AttributeConstants::$PROD_ATTR_ARRAY)."') OR attr_key LIKE '".AttributeConstants::VIDEO."%') AND add_date BETWEEN '".$startDate."' AND '".$endDate."' GROUP BY attr_key";die;*/
            $res = DB::connection('mysql111')->select("SELECT product_id,COUNT(*) AS total,attr_key FROM product_attributes WHERE (attr_key IN('".implode("','",AttributeConstants::$PROD_ATTR_ARRAY)."') OR (attr_key LIKE '".AttributeConstants::VIDEO."%' AND attr_key LIKE '%url')) AND add_date BETWEEN '".$startDate."' AND '".$endDate."' GROUP BY attr_key");
            $lastWeekCount = $this->getCountOfRecords($res);
            return $lastWeekCount;
        }catch (\Exception $e) {

        }
    }

    /**
     * @return ContentSummary
     */
    public function getCurrentWeekRecords(){
        try {
            $startDate=date("Y-m-d",strtotime(AttributeConstants::MONDAY_THIS_WEEK));
            $endDate=date("Y-m-d",strtotime(AttributeConstants::SUNDAY_THIS_WEEK));
            $res = DB::connection('mysql111')->select("SELECT product_id,COUNT(*) AS total,attr_key FROM product_attributes WHERE (attr_key IN('".implode("','",AttributeConstants::$PROD_ATTR_ARRAY)."') OR (attr_key LIKE '".AttributeConstants::VIDEO."%' AND attr_key LIKE '%url')) AND add_date BETWEEN '".$startDate."' AND '".$endDate."' GROUP BY attr_key");
            $currentCount = $this->getCountOfRecords($res);
            return $currentCount;
        }catch (\Exception $e) {

        }
    }


    /**
     * @return ContentSummary
     */
    public function getAllRecords() {
        try {
            $res = DB::connection('mysql111')->select("SELECT product_id,COUNT(*) AS total,attr_key FROM product_attributes WHERE (attr_key IN('".implode("','",AttributeConstants::$PROD_ATTR_ARRAY)."') OR (attr_key LIKE '".AttributeConstants::VIDEO."%' AND attr_key LIKE '%url')) GROUP BY attr_key");
            $Allcount = $this->getCountOfRecords($res);
            return $Allcount;
        } catch (\Exception $e) {

        }
    }

    /**
     * @param $res
     * @return ContentSummary
     */
    public function getCountOfRecords($res) {
        $objTotalCount = new ContentSummary();
        $attributes = array();
        foreach ($res as $row) {
            if ($row->attr_key == AttributeConstants::VIEW360_IMAGE_COUNT) {
                $objTotalCount->views360Count = $row->total;
            } elseif ($row->attr_key == AttributeConstants::CAMERA_SAMPLES) {
                $objTotalCount->cameraCount = $row->total;
            } elseif ($row->attr_key == AttributeConstants::SCREENSHOTS) {
                $objTotalCount->screenShotCount = $row->total;
            } elseif ($row->attr_key == AttributeConstants::BENCHMARKS) {
                $objTotalCount->benchMarkCount = $row->total;
            } elseif ($row->attr_key == AttributeConstants::SLIDESHARE_REVIEW_URL) {
                $objTotalCount->slideShareCount = $row->total;
            } elseif (stripos($row->attr_key, AttributeConstants::VIDEO) !== FALSE) {
                $objTotalCount->actualVideoCount += $row->total;
                //majburi
                if (!in_array($row->product_id, $attributes)) {
                    array_push($attributes, $row->product_id);
                    $objTotalCount->videoCount += $row->total;
                }
            }
        }
        return $objTotalCount;
    }

    /**
     * @param $category
     * @return array
     */
    public function getLastWeekRecordsDetailsByCategory($category){
        try {

            $startDate = date("Y-m-d", strtotime(AttributeConstants::MONDAY_LAST_WEEK));
            $endDate = date("Y-m-d", strtotime(AttributeConstants::SUNDAY_LAST_WEEK));
            $res = DB::connection('mysql111')->select("SELECT product_id,product_name,attr_key,add_date FROM product_attributes WHERE attr_key = '".$category."' AND add_date BETWEEN '".$startDate."' AND '".$endDate."' ORDER BY add_date");
            if($category == AttributeConstants::VIDEO)
                $res = DB::connection('mysql111')->select("SELECT DISTINCT product_id, product_name,attr_key,add_date FROM product_attributes WHERE (attr_key LIKE '".AttributeConstants::VIDEO."%' AND attr_key LIKE '%url') AND add_date BETWEEN '".$startDate."' AND '".$endDate."' GROUP BY product_id");

            $objLastWeekName = array();
            foreach ($res as $products) {
                $productAttr = new ProductAttr();
                $productAttr->attr_key = $products->attr_key;
                $productAttr->id = $products->product_id;
                $productAttr->name = $products->product_name;
                $productAttr->add_date = $products->add_date;
                $objLastWeekName[] = $productAttr;
            }

            return $objLastWeekName;
        }catch (\Exception $e) {

        }
    }

    /**
     * @param $category
     * @return array
     */
    public function getCurrentWeekRecordsDetailsByCategory($category){
        try {

            $startDate = date("Y-m-d", strtotime(AttributeConstants::MONDAY_THIS_WEEK));
            $endDate = date("Y-m-d", strtotime(AttributeConstants::SUNDAY_THIS_WEEK));
            $res = DB::connection('mysql111')->select("SELECT product_id,product_name,attr_key,add_date FROM product_attributes WHERE attr_key = '".$category."' AND add_date BETWEEN '".$startDate."' AND '".$endDate."' ORDER BY add_date");
            if($category == AttributeConstants::VIDEO)
                $res = DB::connection('mysql111')->select("SELECT DISTINCT product_id, product_name,attr_key,add_date FROM product_attributes WHERE (attr_key LIKE '".AttributeConstants::VIDEO."%' AND attr_key LIKE '%url') AND add_date BETWEEN '".$startDate."' AND '".$endDate."' GROUP BY product_id");

            $objCurrentWeekName = array();
            foreach ($res as $products) {
                $productAttr = new ProductAttr();
                $productAttr->attr_key = $products->attr_key;
                $productAttr->id = $products->product_id;
                $productAttr->name = $products->product_name;
                $productAttr->add_date = $products->add_date;
                $objCurrentWeekName[] = $productAttr;
            }

            return $objCurrentWeekName;
        }catch (\Exception $e) {

        }
    }
    /**
     * @return ProductAttr
     */
    public function getLastWeekRecordDetailsDownload() {
        try {
            $startDate = date("Y-m-d", strtotime(AttributeConstants::MONDAY_LAST_WEEK));
            $endDate = date("Y-m-d", strtotime(AttributeConstants::SUNDAY_LAST_WEEK));
            $res = DB::connection('mysql111')->select("SELECT DISTINCT product_id,product_name FROM product_attributes WHERE add_date BETWEEN '".$startDate."' AND '".$endDate."'");
            $result =array();
            foreach ($res as $products) {
                $productAttr = $this->getProductAttr($products->product_id,true);
                $productAttr->id = $products->product_id;
                $productAttr->name = $products->product_name;
                $result[] = $productAttr;
            }
            return $result;
        }catch (\Exception $e) {

        }

    }

    /**
     * @param $temp_video
     * @return mixed
     */
    public static function Insert ($temp_video) {
        $res = DB::connection('mysql111')->table('product_attributes')->insert($temp_video);
        return $res;
    }

    /**
     * @param $productId
     * @param $catId
     * @return float
     */
    public static function getVideoCount ($productId, $catId) {
        $res = DB::connection('mysql111')->table('product_attributes')->where('product_id', $productId) ->where('category_id', $catId) ->where('status', 1) ->count();
        return $res/2;
    }

    /**
     * @param $videoId
     * @return mixed
     */
    public static function getVideoTitleFromUrl ($videoId) {
        $content = file_get_contents("http://youtube.com/get_video_info?video_id=".$videoId);
        parse_str($content, $ytarr);
        return $ytarr['title'];
    }

    /**
     * @param $productId
     * @param $catId
     * @param $url
     * @return mixed
     */
    public static function deleteEntry($productId, $catId, $url) {
        $res = DB::connection('mysql111')->table('product_attributes')->where('product_id', $productId) ->where('category_id', $catId) ->where('attr_value', $url) -> delete();;
        return $res;
    }

    /**
     * @param $productId
     * @param $catId
     * @param $url
     * @return mixed
     */
    public static function getProductAttrKey($productId, $catId, $url) {
        $res = DB::connection('mysql111')->table('product_attributes')->select('attr_key')->where('product_id', $productId) ->where('category_id', $catId) ->where('attr_value', $url) -> get();
        return $res;
    }

    /**
     * @param $productId
     * @param $catId
     * @param $url
     * @return mixed
     */
    public static function getAddDate($productId, $catId, $url) {
        $res = DB::connection('mysql111')->table('product_attributes')->select('add_date')->where('product_id', $productId) ->where('category_id', $catId) ->where('attr_value', $url) -> get();
        return $res;
    }
    /**
     * @param $productId
     * @param $catId
     * @param $type
     * @param $url
     */
    public static function DeleteFromProductAttributes($productId, $catId, $type, $url) {
        $redis = new RedisUtils();
        if ($type == ItemType::$YoutubeType) {
            for($i = 0; $i<2; $i++) {
                if($i == 1) {

                    $videoId = explode('=', $url);
                    $url = '';
                    $url = self::getVideoTitleFromUrl($videoId[1]);
                    $title = AllProductAttributes::getProductAttrKey($productId, $catId, $url);
                    $redis->deleteIntoRedis($productId, $catId, ItemType::$YoutubeType, $title[0]->attr_key);
                    $res = AllProductAttributes::deleteEntry($productId, $catId, $url);
                } elseif ($i == 0) {
                    $res = AllProductAttributes::deleteEntry($productId, $catId, $url);
                }
            }
        } elseif ($type == ItemType::$SlideShareType) {
            AllProductAttributes::deleteEntry($productId, $catId, $url);
            $url = 'slideshare_url';
            $redis -> deleteIntoRedis($productId, $catId, ItemType::$SlideShareType, $url) ;
        }
        $elastic = new ElasticUtils();
        $elastic->datalayerFeed($productId);
    }
    public static function DeleteAllByGalleryId($productId, $catId, $items) {
        $url = array();
        foreach ($items as $item) {
            self::DeleteFromProductAttributes($productId, $catId, $item->type, $item->url);
        }
    }

    public static function InsertIntoProductAttributes($galleryId, $productName, $productId, $productCatId, $dataVideos, $isEdit) {
        try {
            $redis = new RedisUtils();
            $displayOrder = 0;
            $isDeleted = false;
            foreach ($dataVideos as $items) {
                if ($isEdit == 0) {
                    $displayOrder++;
                } elseif ($isEdit == 1) {
                    $displayOrder =  $items->display_order;
                }
                //die;
                $statusValue = 0;
                $temp_video = array();
                $temp_video['product_id'] = $productId;
                $temp_video['product_name'] = $productName;
                $temp_video['category_id'] = $productCatId;
                $temp_video['status'] = $items->active;
                $attr_key = '';
                if ($items->type == ItemType::$YoutubeType) {
                    for($i = 0; $i<2; $i++) {
                        if($i == 1) {
                                $attr_key = 'video' . $displayOrder . '_title';
                                $videoId = explode('=', $items->url);
                                $attr_value = '';
                                $attr_value = self::getVideoTitleFromUrl($videoId[1]);
                                $temp_video['attr_key'] = $attr_key;
                                $temp_video['attr_value'] = $attr_value;
                                if(!isset($items->created_at)) {
                                    $items->created_at = AllProductAttributes::getAddDate($productId, $productCatId, $attr_value);
                                }
                                $temp_video['add_date'] = $items->created_at;
                                $temp_video['edit_date'] = isset($items->updated_at)? $items->updated_at : $items->created_at;
                                $response = AllProductAttributes::deleteEntry($productId, $productCatId, $attr_value);
                                $res = AllProductAttributes::Insert($temp_video);
                                if ($res) {
                                    $key1 = $attr_key;
                                    $value1 = $attr_value;
                                    $statusKey =  'video' . $displayOrder . '_status';
                                }

                        } elseif ($i == 0) {
                                $attr_key = 'video' . $displayOrder . '_url';
                                $attr_value = $items->url;
                                $temp_video['attr_key'] = $attr_key;
                                $temp_video['attr_value'] = $attr_value;
                                if(!isset($items->created_at)) {
                                    $items->created_at = AllProductAttributes::getAddDate($productId, $productCatId, $attr_value);
                                }
                                $temp_video['add_date'] = $items->created_at;
                                $temp_video['edit_date'] = isset($items->updated_at)? $items->updated_at : $items->created_at;
                                $response = AllProductAttributes::deleteEntry($productId, $productCatId, $attr_value);
                                $res = AllProductAttributes::Insert($temp_video);
                                if ($res) {
                                    $key2 = $attr_key;
                                    $value2 = $attr_value;
                                    $statusValue = 1;
                                }
                        }
                    }
                    if ($statusValue == 1) {
                        $data = array(
                            "$key1" => $value1,
                            "$key2" => $value2,
                            "$statusKey" => $statusValue
                        );
                        if(!$isDeleted) {
                            $redis -> deleteCompleteHash($productId, $productCatId, ItemType::$YoutubeType);
                            $isDeleted = true;
                        }
                        $redis -> insertIntoRedis($productId, $productCatId, ItemType::$YoutubeType, $data);
                    }

                } elseif ($items->type == ItemType::$SlideShareType) {
                    $attr_key = 'slideshare_review_url';
                    $attr_value = $items->url;
                    $temp_video['attr_key'] = $attr_key;
                    $temp_video['attr_value'] = $attr_value;
                    if(!isset($items->created_at)) {
                        $items->created_at = AllProductAttributes::getAddDate($productId, $productCatId, $attr_value);
                    }
                    $temp_video['add_date'] = $items->created_at;
                    $temp_video['edit_date'] = isset($items->updated_at)? $items->updated_at : $items->created_at;
                    $response = AllProductAttributes::deleteEntry($productId, $productCatId, $attr_value);
                    $res = AllProductAttributes::Insert($temp_video);
                    if ($res) {
                        $data = array(
                            "slideshare_url" => $attr_value,
                            "slideshare_status" => 1,
                        );
                        if(!$isDeleted) {
                            $redis -> deleteCompleteHash($productId, $productCatId, ItemType::$SlideShareType);
                            $isDeleted = true;
                        }
                        $redis -> insertIntoRedis($productId, $productCatId, ItemType::$SlideShareType, $data);
                    }
                }

            }
            $elastic = new ElasticUtils();
            $elastic->datalayerFeed($productId);
        }catch (\Exception $e) {

        }

    }

}
