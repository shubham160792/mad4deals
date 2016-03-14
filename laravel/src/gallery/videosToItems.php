<?php
require __DIR__.'/config/'.$_SERVER['environment'].'/config.php';
require 'utils/Connection.php';
error_reporting(E_ALL);
ini_set('display_errors',0);
require 'crons/CreateGallery.php';
$obj = new crons\CreateGallery();
//$mysqlToElastic = new crons\MySQLToElastic();
//$cat = \Config::VIDEOS_CATEGORY;

     $obj->doTaskVideosToItems();
