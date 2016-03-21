<?php

/**
 * This class handles all the user-related functions
 * @extends \auth
 * @author piyush
 */
require(__DIR__  . "/../auth/auth.php");

class user extends auth {

    /**
     *
     * @var array $user This variable stores all the information about the user
     */
    public $user = array();

    /**
     * Constructor of the class
     * @global type $_SESSION The Superglobal session variable
     */
    public function __construct() {
        parent::__construct();
        global $_SESSION;
        if ($this->check_session() == true) {
            //populate user data and set class variables to authenticated else normal
            $this->populate_data($_SESSION['id']);
            
            //Insert this recent activity time in the database
            $this->update_lastlogin();
        }
    }

    /**
     * This function is used to log-in the user and populate the $user variable
     * with all the user data. Returns true if successful else false
     * @global array $_SESSION The Superglobal Session variable
     * @param string $username The username of the user
     * @param string $password The password of the user
     * @return boolean
     */
    function login($username, $password) {
        global $_SESSION;

        //Login
        $ret = $this->authenticate($username, $password);

        //Populate the user array only if user has logged in
        if ($ret == true and !is_array($ret)) {
            $this->populate_data($_SESSION['id']);
        }
        
        //And return as it was previously doing before populate data was added
        return $ret;
    }

    /**
     * This function is used to log-out the user and destroy the session
     * @return null
     */
    function logout() {
        //Remove all the session variables and remove user data
        $this->unauthenticate();
        $this->remove_data();
    }

    /**
     * This function is used to populate the $user variable with all the user data
     * @global \db $db The instance of the db class
     * @param integer $id The ID of the user to fetch the data of.
     * @return null
     */
    function populate_data($id) {
        // Fill user variable with user data
        global $db;
        $query = $db->query("Select * from member where id=$id");
        $row = $db->fetch($query);
        $this->user = $row;
    }

    /**
     * This is the opposite of populate_data() function. This function is used
     * to remove the data from the $user variable.
     * @return null
     */
    function remove_data() {
        //Remove data filled in user variable
        unset($this->user);
        $this->user = array();
    }
    
    /**
     * This function is used to get the last activity time of the user
     * @global \db $db Instance of the db class
     * @return boolean True if successfull else false on unsuccessfull
     */
    function update_lastlogin()
    {
        global $db;
        if ($db->query("update member set lastlogin=" . time(). " where id=". $this->user['id']))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    /**
     * This function is used to get the last login time of the user
     * @global \db $db Instance of the db class
     * @return integer TimeStamp of the last login
     */
    function get_lastlogin()
    {
        global $db;
        $query = $db->query("select lastlogin from member where id=".$this->user['lastlogin']);
        
        $row = $db->fetch($query);
        
        return $row['lastlogin'];
    }

}

?>
