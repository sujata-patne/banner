<?php

namespace VOP\Utils;

use PDO;

class PdoUtils {

    public static function obtainConnection($dbName) {

        global $config;
		 
        $mode = $config['current_mode'];
        
        if($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == 'localhost:8080')
        {
            $mode= 'local';
        }
        else if($_SERVER['HTTP_HOST'] == '192.168.1.174'){
            $mode = "dev";

        }
        else if($_SERVER['HTTP_HOST'] == '192.168.1.159')
        {
            $mode = 'staging';
        }
        else if($_SERVER['HTTP_HOST'] == '103.43.2.9')
        {
            $mode = 'production';
        }
        else
        {
            $mode= 'local';

        }
        
        $host = $config[$mode]['DB'][$dbName]['db_host'];
        $port = $config[$mode]['DB'][$dbName]['db_port'];
        $db_name = $config[$mode]['DB'][$dbName]['db_name'];
        $username = $config[$mode]['DB'][$dbName]['db_user'];
        $password = $config[$mode]['DB'][$dbName]['db_pass'];
    	
        try {
            /*$dbConnection = new PDO('psql:host=' . $host . ';' .
                    'port=' . $port . ';' .
                    'dbname=' . $db_name . ';' .
                    'user=' . $username . ';' .
                    'password=' . $password
            ); */
           $dbConnection = new PDO("mysql:host=" . $host . ";" .
            		"dbname=" . $db_name . ";charset=utf8;"
            		, $username
            		, $password
            ); 
           

     
            $dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $dbConnection;
            
        } catch (\Exception $e) {
            echo("Unable to obtain database connection: " );
            print_r( $e->getMessage() );
            return null;
        }
    }

    public static function closeConnection(&$dbConnection) {
        try {
            $dbConnection = null;
        } catch (\Exception $e) {
        	echo("Unable to close database connection: " );
            print_r( $e->getMessage() );
        }
    }

}

