<?php
/**
 * Created by PhpStorm.
 * User: boss
 * Date: 17/7/14
 * Time: 1:38 AM
 */

require_once('log4php/Logger.php');
class LoggerFactory {

    private static $init=false;

    private function  __construct(){

    }


    public static function getInstance($name){
        if ( self::$init == false){
            Logger::configure(dirname(__DIR__)."/config/log4php.xml");
            self::$init = true;
        }
        return Logger::getLogger($name);
    }
}
?>
