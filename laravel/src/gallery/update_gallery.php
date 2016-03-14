<?php
error_reporting(E_ALL);
ini_set('display_errors',0);
require __DIR__.'/config/'.$_SERVER['environment'].'/config.php';
require 'utils/Connection.php';
require 'utils/ElasticSearchUtils.php';
require 'crons/CreateGallery.php';
require 'crons/MySQLToElastic.php';
$obj = new crons\CreateGallery();
$mysqlToElastic = new crons\MySQLToElastic();
$elastic = new \utils\ElasticSearchUtils();
$cat = \Config::DESIGN_CATEGORY;
if(isset($argv[1]) || isset($_REQUEST['id'])){
    if(is_numeric($_REQUEST['id'])){
        $id = $_REQUEST['id'];
    }elseif(is_numeric(trim(strtolower($argv[1])))){
        $id = $argv[1];
    }
    $gallery_id = $obj->doTask($id);
    if(is_numeric($gallery_id)){
        $elastic->deleteElasticGallery($gallery_id);
    }
    $mysqlToElastic->doTask($id,$cat);
}else{
    $date = date('Y-m-d', strtotime('-1 day'));
    $ids = $mysqlToElastic->getNewlyUpdatedProducts($date);
    for($i=0;$i<count($ids);$i++){
        $id = $ids[$i];
        $gallery_id = $obj->doTask($id);
        if(is_numeric($gallery_id)){
            $elastic->deleteElasticGallery($gallery_id);
        }
        $mysqlToElastic->doTask($id,$cat);
        unset($id);
        unset($gallery_id);
    }
}
?>
