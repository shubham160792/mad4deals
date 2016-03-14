<?php
namespace App\utils;

use \Logger;

class LoggerFactory{

      public static function getInstance($class){

            $logger = Logger::getLogger($class);
            Logger::configure(__DIR__.'/../config/logger_config.xml');
            return $logger;
      }
}
?>