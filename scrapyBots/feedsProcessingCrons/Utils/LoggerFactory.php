<?php
class LoggerFactory {

    private function  __construct(){

    }
    public static function getInstance($name){
        Logger::configure(ConstantUtils::getRootDir()."/Class/config/log4php.xml");
        return Logger::getLogger($name);
    }
}
?>
