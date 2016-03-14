<?php
namespace utils;
use \PDO;

class Connection {

	public function getDbConnection () {

		try{
            $db_conn =  new \PDO("mysql:host=".\Config::$connection['_mysql_mobiles']['host'].";dbname=".\Config::$connection['_mysql_mobiles']['db']."" , \Config::$connection['_mysql_mobiles']['username'], \Config::$connection['_mysql_mobiles']['password']);
            $db_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $db_conn;
        }
        catch(\Exception $e){
            return false;
        }

	}
    public function getDbConnection111 () {

        try{
            $db_conn =  new \PDO("mysql:host=".\Config::$connection['_mysql_111']['host'].";dbname=".\Config::$connection['_mysql_111']['db']."" , \Config::$connection['_mysql_111']['username'], \Config::$connection['_mysql_111']['password']);
            $db_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $db_conn;
        }
        catch(\Exception $e){
            return false;
        }

    }
    public function getRedisConnection () {

        try{
            $redis=new \Redis();
            $redis->connect(\Config::$connection['redis']['ip']);
            return $redis;
        }
        catch(\Exception $e){
            return false;
        }

    }
}