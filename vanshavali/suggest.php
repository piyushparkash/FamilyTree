<?php

/**
 * Suggestion Class
 *
 * @author piyush
 */
class suggest {

    public $id;

    function __construct($suggestid) {
        $this->id = $suggestid;
    }

    function add_son_suggest($name, $gender, $id) {
        global $db, $user;

        //fill array with data
        $finalarray = array('name' => $name, 'gender' => $gender, 'sonof' => $id);

        //Put it in database
        //echo "insert into suggested_info (typesuggest,suggested_value,suggested_by,ts) values('child', '" .
        //  json_encode($finalarray) . "'," . $_COOKIE['id'] . "," . time();

        $db->query("insert into suggested_info (typesuggest,suggested_value,suggested_by,ts) values('child', '" .
                json_encode($finalarray) . "'," . $user->user['id'] . "," . time() . ")");
    }

    function remove_suggest($id) {
        global $db, $user;

        $query = $db->query("insert into suggested_info (typesuggest,suggested_value,suggested_by,ts) values
            ('remove', '$id'," . $user->user['id'] . "," . time() . ")");
    }

    function edit_suggest($name, $gender, $relationship, $dob, $alive, $id) {
        global $db, $user;

        preg_match("/([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4,4})/", $dob, $matches);
        $dob = mktime(0, 0, 0, $matches[2], $matches[1], $matches[3]);

        $finalarray = array('name' => $name,
            'gender' => $gender,
            'relationship' => $relationship,
            'dob' => $dob,
            'alive' => $alive,
            'id' => $id);

        $db->query("insert into suggested_info (typesuggest,suggested_value,suggested_by,ts) values
            ('edit', '" . json_encode($finalarray) . "'," . $user->user['id'] . "," . time() . ")");
    }
    
    function approve()
    {
        //Approves the $id provided in the constructor
    }
    
    function reject()
    {
        //Rejects the $id provided in the constructor
    }
    
    function dontknow()
    {
        //Marks suggestion as don'tknow
    }

}

?>
