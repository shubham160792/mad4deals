<?php
/**
 * Created by PhpStorm.
 * User: shubham
 * Date: 10/11/15
 * Time: 12:09 PM
 */

ini_set('memory_limit', '-1');
//ini_set('auto_detect_line_endings', TRUE);
#Include the SDK using the Composer autoloader
// Include and configure log4php

include_once('Utils/ConstantUtils.php');
require ConstantUtils::getRootDir().'/vendor/autoload.php';
require ConstantUtils::getRootDir().'/Class/FeedsProcessingClass.php';
require ConstantUtils::getRootDir().'/Utils/LoggerFactory.php';

#getting current timezone from constantUtils time
date_default_timezone_set(ConstantUtils::DEFAULT_TIMEZONE);
$obj = new FeedsProcessingClass();

$result=$obj->insertSerializedData();
echo "<pre>";
print_r($result);
echo "</pre>";