<?php
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

echo "Enter a source below\n";
$handle = fopen ("php://stdin","r");
$source = fgets($handle);
$source = trim($source);

echo "Enter crawl type below.Available options are\n";
echo ConstantUtils::LISTINGS_DETAILS."(Default Value)\n";
echo ConstantUtils::AGENT_DETAILS."\n";

$handle = fopen ("php://stdin","r");
$crawlType = fgets($handle);
$crawlType = trim($crawlType);

echo "Enter date below(format.yyyy-mm-dd)(Default Value: Today's date)\n";
$handle = fopen ("php://stdin","r");
$date = fgets($handle);
$date = trim($date);


if(empty($crawlType)){
    $crawlType=ConstantUtils::LISTINGS_DETAILS;
}
if(empty($date)){
    $date=date('Y-m-d');
}

if(!empty($date) && !empty($source) && !empty($crawlType)){
    $result=$obj->doTask($date,$source,$crawlType);
}
?>