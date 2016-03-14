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
$cat = $_REQUEST['category'];
if(isset($argv[1]) || isset($_REQUEST['id'])){
    if(is_numeric($_REQUEST['id'])){
        $id = $_REQUEST['id'];
    }elseif(is_numeric(trim(strtolower($argv[1])))){
        $id = $argv[1];
    }
    if(is_numeric($id) && $id > 0){
        $mysqlToElastic->doTask($id);
    }
}
?>
