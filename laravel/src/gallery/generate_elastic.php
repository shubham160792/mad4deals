<?php
error_reporting(E_ALL);
ini_set('display_errors',1);
require __DIR__.'/config/'.$_SERVER['environment'].'/config.php';
require 'utils/Connection.php';
require 'crons/MySQLToElastic.php';
$obj = new crons\MySQLToElastic($client);
$obj->doTask();
?>