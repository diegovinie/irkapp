<?php
/**
 * @version 0.1 09ABR18
 * @author diego.viniegra@gmail.com
 */
 
// depende de settings.php
// depende de hlp

namespace Database;

use PDO;

class PDOe extends PDO
{
    /**
     * Conecta a la base de datos
     *
     * @return PDOe ó bool false en caso de error
     */
    static function connect()
    {
        $options = array(
            // Para asegurar la compatibilidad UTF8
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

    /**
     * Crea una base de datos
     *
     * @return bool true ó false en caso de error
     */
    static function createDatabase(/*string*/ $name=null)
    {
        // si no se introdujo nombre usará el predefinido en settings.php
        $dbName = $name? $name : DB_NAME;

        $options = array(
            // Para asegurar la compatibilidad UTF8
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
            \hlp\logger('Problema al conectar a la base de datos: '.$err->getMessage(), true);
            return false;
        }

        $exe = $db->exec("
            DROP DATABASE IF EXISTS $dbName;
            CREATE DATABASE IF NOT EXISTS $dbName;"
        );

        if(!$exe){
            \hlp\logger($db->errorInfo()[2], true);
            return false;
        }
        else{
            return true;
        }
    }
}
