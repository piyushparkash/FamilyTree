<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of db
 * @package db
 * @author piyush
 */
require_once '../header.php';
global $config, $template;

class db {

    public $connection = false;

    public function __construct() {
        
    }

    public function connect($host = null, $username = null, $password = null,$database=null) {

        //if null then assign the defualt
        $host=$host==NULL ? $config['host'] : $host;
        $username=$username==null ? $config['username'] : $username;
        $password=$password==null ? $config['password'] : $password;
        $database=$database==null ? $config['database'] : $database;
        if ($this->connection != false) {
            $this->connection = mysql_connect($host, $username, $password);
            if ($this->connection == false) {
                trigger_error("Cannot connect to database", E_USER_ERROR); //report error in case of failure
                return false;
            }
        }
        if (!mysql_select_db($database)) {
            trigger_error("Cannot select database. Probably Database is not installed or something else", E_USER_ERROR);
            return false;
        }
        return true;
    }

    function query($sql) {
        //Check if it is connected to database
        if ($this->connection != false) {
            $query = mysql_query($sql);
            if ($query == false) {
                //Some error occured while querying
                trigger_error(mysql_error(), E_USER_ERROR);
                return false;
            } else {
                //return the resource
                return $query;
            }
        } else {
            //Forgot to establish connection
            trigger_error("Establish Connection to database before executing query", E_USER_ERROR);
            return false;
        }
    }
    
    function fetch($query)
    {
        if ($query!=false)
        {
            return mysql_fetch_array($query);
        }
        else
        {
            trigger_error("Invalid Query resource provided",E_USER_ERROR);
            return false;
        }
    }

}

?>
