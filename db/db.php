<?php

/**
 * Description of db
 * @package db
 * @author piyush
 */
class db {

    public $connection = false;

    public function __construct() {
        $this->connect();
    }

    /**
     * Connects to a database
     * @param string $host
     * @param string $username
     * @param string $password
     * @param string $database
     * @return boolean
     */
    public function connect($host = null, $username = null, $password = null, $database = null) {
        global $config;

        //if null then assign the defualt
        $host = ($host == NULL) ? $config['host'] : $host;
        $username = $username == null ? $config['username'] : $username;
        $password = $password == null ? $config['password'] : $password;
        $database = $database == null ? $config['database'] : $database;
        if (!empty($host) && !empty($username) && !empty($password)) {
            $this->connection = mysql_connect($host, $username, $password);
            if ($this->connection == false) {
                trigger_error("Cannot connect to database", E_USER_ERROR); //report error in case of failure
                return false;

                if (!is_null($database)) {
                    if (!mysql_select_db($database)) {
                        trigger_error("Cannot Select database.", E_USER_ERROR);
                        return false;
                    }
                }
            }
        }
        return true;
    }

    /**
     * Select Database
     * @param string $name
     * @return boolean
     */
    function select_db($name) {
        if (!mysql_select_db($name)) {
            trigger_error("Cannot Select Database", E_USER_ERROR);
            return false;
        }
    }

    /**
     * Executes a SQL Query
     * @param string $sql
     * @return boolean
     */
    function query($sql) {
        //Check if it is connected to database
        if ($this->connection != false) {
            $query = mysql_query($sql);
            if ($query == false) {
                //Some error occured while querying
                trigger_error(mysql_error(), E_USER_NOTICE);
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

    /**
     * Fetches a row from the query resource
     * @param resource $query
     * @return boolean
     */
    function fetch($query) {
        if ($query != false) {
            return mysql_fetch_array($query);
        } else {
            trigger_error("Invalid Query resource provided", E_USER_NOTICE);
            return false;
        }
    }

    /**
     *  @param SQL $sql Sql query to be executed
     * @return array First row array of the query
     */
    function get($query) {
        if ($query != false) {
            return ($this->fetch($this->query($query)));
        } else {
            trigger_error("Invalid SQL Query String", E_USER_NOTICE);
            return false;
        }
    }

}

?>
