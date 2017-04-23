<?php

/**
 * This class handles all the interaction with the database
 * @package db
 * @author piyush
 */
class db {

    public $connection = false;

    public function __construct() {
        $this->connect();
    }

    /**
     * Connects to a database and returns true if connected
     * @param string $host Host of the MySQL Database
     * @param string $username The username of the MySQL Database
     * @param string $password The password of the MySQL Database
     * @param string $database The database fetch the data from
     * @return boolean
     */
    public function connect($host = null, $username = null, $password = null, $database = null) {
        global $config;

        //if null then assign the defualt
        $host = ($host == NULL) ? $config['host'] : $host;
        $username = $username == null ? $config['username'] : $username;
        $password = $password == null ? $config['password'] : $password;
        $database = $database == null ? $config['database'] : $database;
        if (!empty($host) && !empty($username) && !is_null($password)) {
            $this->connection = mysqli_connect($host, $username, $password);
            if ($this->connection == false) {
                trigger_error("Cannot connect to database", E_USER_ERROR); //report error in case of failure
                return false;

                if (!is_null($database)) {
                    if (!mysqli_select_db($database)) {
                        trigger_error("Cannot Select database.", E_USER_ERROR);
                        return false;
                    }
                }
            }
        }
        return true;
    }

    /**
     * This function is used to select a database
     * @param string $name The name of the databse to select
     * @return boolean
     */
    function select_db($name) {
        if (!mysqli_select_db($this->connection, $name)) {
            trigger_error("Cannot Select Database", E_USER_ERROR);
            return false;
        }
    }

    /**
     * Executes a SQL Query and returns the resource of the result set
     * It returns false and triggers the default error function if connection to
     * database if not already established
     * @param string $sql The SQL to be executed
     * @return resource
     */
    function query($sql) {
        //Check if it is connected to database
        if ($this->connection != false) {
            $query = mysqli_query($this->connection, $sql);
            if ($query == false) {
                //Some error occured while querying
                trigger_error(mysqli_error($this->connection), E_USER_NOTICE);
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
     * Now mysqli needs sql connection to get the last_insert_id. So I had to
     * make this wrapper around the function.
     * @return type
     */
    function last_id() {
        return mysqli_insert_id($this->connection);
    }

    function real_escape_string($noescapestring)
    {
        return mysqli_real_escape_string($this->connection, $noescapestring);
    }

    /**
     * Fetches a row from the query resource. Triggers error if invalid resource
     * is provided
     * @param resource $query The query resource returned bt query function
     * @return array
     */
    function fetch($query) {
        if ($query === true) {
            return true;
        } else if ($query != false) {
            return mysqli_fetch_array($query);
        } else {
            trigger_error("Invalid Query resource provided", E_USER_NOTICE);
            return false;
        }
    }
    /**
     * 
     * @param type $query
     * @return boolean
     */
    function fetch_all($query)
    {
        $rows = $finalarray = array();
        if ($query === true) {
            return true;
        } else if ($query != false) {
            while($rows = mysqli_fetch_array($query))
            {
                $finalarray[] = $rows;
            }
            return $finalarray;
        } else {
            trigger_error("Invalid Query resource provided", E_USER_NOTICE);
            return false;
        }
    }

    /**
     * This function can be used when only a single row is to be fetched from
     * database
     *  @param SQL $sql Sql query to be executed
     *  @return array First row array of the query
     */
    function get($query) {
        if ($query != false) {
            return ($this->fetch($this->query($query)));
        } else {
            return false;
        }
    }

}

?>
