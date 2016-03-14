<?php
require __DIR__.'/config/'.$_SERVER['environment'].'/config.php';
require 'utils/Connection.php';
require 'crons/CreateGallery.php';
$obj = new crons\CreateGallery();
$obj->doTask();
?>
