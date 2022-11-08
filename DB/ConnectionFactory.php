<?php
namespace DB;
use PDO;

class ConnectionFactory
{

    public static array $connexion;

    public static function setConfig( $file )
    {
        self::$connexion=parse_ini_file($file);
    }

    public static function makeConnection():PDO{
        self::setConfig("DB/config.ini");
        $dsn=self::$connexion['driver'].':host='.self::$connexion['host'].':'.self::$connexion['port'].';dbname='.self::$connexion['database'];
        if(isset(self::$connexion['username'])&&isset(self::$connexion['password'])){
            return new PDO($dsn,self::$connexion['username'],self::$connexion['password']);
        }else{
            return new PDO($dsn);
        }
    }

}