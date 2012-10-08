<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of user
 *
 * @author piyush
 */

require("../auth/auth.php");

class user extends auth {
    public $user=array();
    public function __construct() {
        parent::__construct();
        if ($this->check_session()==true)
        {
            //populate user data and set class variables to authenticated else normal
        }
    }
    
    
    function login($username,$password)
    {
        return authenticate($username,$password);
    }
    
    function populate_data()
    {
        
    }
}

?>
