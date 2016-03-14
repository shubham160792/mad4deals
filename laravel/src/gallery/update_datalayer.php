<?php
require __DIR__.'/config/'.$_SERVER['environment'].'/config.php';
require 'utils/Connection.php';
error_reporting(E_ALL);
ini_set('display_errors',0);
require 'utils/ElasticSearchUtils.php';
require 'crons/MySQLToElastic.php';
$mysqlToElastic = new crons\MySQLToElastic();
$elastic = new \utils\ElasticSearchUtils();
echo $gl_category = \Config::CAMERA_SAMPLES_CATEGORY;
$products = $elastic->getProductIdsFromElastic($gl_category);
for($i=0;$i<count($products);$i++){
    $status = $mysqlToElastic->insertProductAttribute($products[$i]['pro_id'],$products[$i]['pro_name'],$products[$i]['cat_id'],$gl_category);
    if($status){
        $mysqlToElastic->updateDatalayerFeed($products[$i]['pro_id'],$products[$i]['cat_id']);
    }
}
echo $gl_category = \Config::SCREEN_SHOTS_CATEGORY;
$products = $elastic->getProductIdsFromElastic($gl_category);
for($i=0;$i<count($products);$i++){
    $status = $mysqlToElastic->insertProductAttribute($products[$i]['pro_id'],$products[$i]['pro_name'],$products[$i]['cat_id'],$gl_category);
    if($status){
        $mysqlToElastic->updateDatalayerFeed($products[$i]['pro_id'],$products[$i]['cat_id']);
    }
}
echo $gl_category = \Config::BENCHMARK_CATEGORY;
$products = $elastic->getProductIdsFromElastic($gl_category);
for($i=0;$i<count($products);$i++){
    $status = $mysqlToElastic->insertProductAttribute($products[$i]['pro_id'],$products[$i]['pro_name'],$products[$i]['cat_id'],$gl_category);
    if($status){
        $mysqlToElastic->updateDatalayerFeed($products[$i]['pro_id'],$products[$i]['cat_id']);
    }
}
?>
