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
    public $id;
    public function __construct($memberid) {
        $this->id = $memberid;
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

    function remove() {
        global $db,$user;
        
        $query=$db->query("insert into suggested_info (typesuggest,suggested_value,suggested_by,ts) values
            ('remove', '$this->id',". $user->user['id'].",".time().")");
    }

    function edit($type) {
        global $db,$user;
    }

}

?>
