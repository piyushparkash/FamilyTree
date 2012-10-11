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

require("auth/auth.php");

class user extends auth {
    public $user=array();
    public function __construct() {
        parent::__construct();
        global $_SESSION;
        if ($this->check_session()==true)
        {
            //populate user data and set class variables to authenticated else normal
            $this->populate_data($_SESSION['id']);
        }
    }
    
    
    function login($username,$password)
    {
        return $this->authenticate($username,$password);
    }
    
    function populate_data($id)
    {
        global $db;
        $query=$db->query("Select * from member where id=$id");
        $row=$db->fetch($query);
        $this->user=$row;
    }
}

?>
