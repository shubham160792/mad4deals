<?php
//ini_set("display_errors", "ON");
//error_reporting(E_ALL);
require __DIR__.'/config/'.$_SERVER['environment'].'/config.php';
require 'utils/Connection.php';
require 'classes/Gallery.php';
$obj = new classes\Gallery();
$category_id=$_GET['category_id'];
$gallery_count=(int)$_GET['count'];
$page=(int)$_GET['page'];
if($page != 0)
{
$page=$page-1;
}
$page=($page*20);
$val=$obj -> getGalleryData($category_id,$gallery_count,$page);
echo $val;
?>