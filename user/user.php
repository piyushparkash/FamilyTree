<?php

/**
 * This class handles all the user-related functions
 * @extends \auth
 * @author piyush
 */
require("auth/auth.php");

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
        }
    }

    /**
     * This function is used to log-in the user and populate the $user variable
     * with all the user data. Returns true if successful else false
     * @global type $_SESSION The Superglobal Session variable
     * @param type $username The username of the user
     * @param type $password The password of the user
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
     * @global type $db The instance of the db class
     * @param type $id The ID of the user to fetch the data of.
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

}

?>
