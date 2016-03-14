<?php
/**
 * Created by PhpStorm.
 * User: balwant
 * Date: 28/7/15
 * Time: 10:57 AM
 */
use App\response\ProductAttr;
use App\utils\ElasticUtils;
class Top200Products extends Eloquent{


    public function _construct() {
    }

    /*/**
     * @param $start
     * @param $batchSize
     * @return \App\response\ProductAttr[]
     */
    public function getTop200Products($start,$batchSize) {

        /** @var \App\response\ProductAttr[] $result */
        $result = array();
        try {
            $objElasticUtils = new ElasticUtils();
            $objProductAttributes = new AllProductAttributes();
            $response = $objElasticUtils->getProductsFromElastic($start,$batchSize);
            $response = json_decode($response, true);

            $response = $response['hits']['hits'];


            for ($i = 0; $i < count($response); $i++) {
                $productAttr = $objProductAttributes->getProductAttr($response[$i]['_source']['id']);
                $productAttr->id = $response[$i]['_source']['id'];
                $productAttr->name = $response[$i]['_source']['pro_search_text'];
                $productAttr->noOfViews = $response[$i]['_source']['view_last30'];
                $result[$productAttr->noOfViews] = $productAttr;
                /*$variants = $response[$i]['_source']['family_json'];

                if(count($variants) > 0) {
                    foreach($variants as $key => $value) {
                        $productAttr = $objProductAttributes->getProductAttr($value['family_mob_id']);
                        $productAttr->id = $value['family_mob_id'];
                        $productAttr->name = $value['family_display_name'];
                        $productAttr->noOfViews = $objElasticUtils->getProductVariantsViews($productAttr->id);
                        $result[$productAttr->noOfViews] = $productAttr;
                    }
                }*/
            }
            return $result;
        } catch (\Exception $e) {

        }
    }
}
