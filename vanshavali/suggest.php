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

    function add_son($name, $gender, $sonof) {
        global $db, $user;

        //fill array with data
        $finalarray = array('name' => $name, 'gender' => $gender, 'sonof' => $sonof);

        //put in the database
        //echo "insert into suggested_info (typesuggest,suggested_value,suggested_by,ts) values('child', '" .
        //  json_encode($finalarray) . "'," . $_COOKIE['id'] . "," . time();

        $db->query("insert into suggested_info (typesuggest,suggested_value,suggested_by,ts) values('child', '" .
                json_encode($finalarray) . "'," . $user->user['id'] . "," . time() . ")");
    }

    function remove() {
        global $db, $user;

        $query = $db->query("insert into suggested_info (typesuggest,suggested_value,suggested_by,ts) values
            ('remove', '$this->id'," . $user->user['id'] . "," . time() . ")");
    }

    function edit($name, $gender, $relationship, $dob, $alive) {
        global $db, $user;

        preg_match("/([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4,4})/", $dob, $matches);
        $dob = mktime(0, 0, 0, $matches[2], $matches[1], $matches[3]);

        $finalarray = array('name' => $name,
            'gender' => $gender,
            'relationship' => $relationship,
            'dob' => $dob,
            'alive' => $alive,
            'id' => $this->id);

        $db->query("insert into suggested_info (typesuggest,suggested_value,suggested_by,ts) values
            ('edit', '" . json_encode($finalarray) . "'," . $user->user['id'] . "," . time() . ")");
    }

}

?>
