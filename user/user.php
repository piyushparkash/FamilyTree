<?php

/**
 * Description of user
 *
 * @author piyush
 */
require("auth/auth.php");

class user extends auth {

    public $user = array();

    public function __construct() {
        parent::__construct();
        global $_SESSION;
        if ($this->check_session() == true) {
            //populate user data and set class variables to authenticated else normal
            $this->populate_data($_SESSION['id']);
        }
    }

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

    function logout() {
        //Remove all the session variables and remove user data
        $this->unauthenticate();
        $this->remove_data();
    }

    function populate_data($id) {
        // Fill user variable with user data
        global $db;
        $query = $db->query("Select * from member where id=$id");
        $row = $db->fetch($query);
        $this->user = $row;
    }

    function remove_data() {
        //Remove data filled in user variable
        unset($this->user);
        $this->user = array();
    }

}

?>
