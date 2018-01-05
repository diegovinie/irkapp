<?php

// depende de settings.php
// depende de hlp

namespace Database;

use PDO;

class PDOe extends PDO
{
    static function connect()
    {
        $options = array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
        );

        try{
            $db = new PDOe(
                "mysql:host=".DB_HOST.";dbname=".DB_NAME,
                DB_USER,
                DB_PWD,
                $options
            );
            return $db;

        }catch(PDOException $err){
            \hlp\logger('Problema al conectar a la base de datos: ' .$err->getMessage() );
            return false;
        }

    }

    static function createDatabase(/*string*/ $name=null)
    {
        $dbName = $name? $name : DB_NAME;

        $options = array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
        );

        try{
            $db = new PDOe(
                "mysql:host=".DB_HOST,
                DB_USER,
                DB_PWD,
                $options
            );

        }catch(PDOException $err){
            \hlp\logger('Problema al conectar a la base de datos: '.$err->getMessage() );
            return false;
        }

        $exe = $db->exec("
            DROP DATABASE IF EXISTS $dbName;
            CREATE DATABASE IF NOT EXISTS $dbName;"
        );

        if(!$exe){
            \hlp\logger($db->errorInfo()[2] );
            return false;
        }
        else{
            return true;
        }
    }
}
