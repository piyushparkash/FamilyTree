<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of suggest
 *
 * @author piyush
 */
class suggest {

    public function __construct($memberid) {
        
    }

    function add_son($name,$gender,$sonof) {
        global $db,$user;

        //fill array with data
        $finalarray=array('name'=>$name, 'gender'=>$gender ,'sonof'=>$sonof);

        //put in the database
        //echo "insert into suggested_info (typesuggest,suggested_value,suggested_by,ts) values('child', '" .
        //  json_encode($finalarray) . "'," . $_COOKIE['id'] . "," . time();

        $db->query("insert into suggested_info (typesuggest,suggested_value,suggested_by,ts) values('child', '" .
                json_encode($finalarray) . "'," . $user->user['id'] . "," . time() . ")");
    }

    function remove_son() {
        
    }

    function edit_member($type) {
        
    }

}

?>
