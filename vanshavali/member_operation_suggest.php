<?php

/**
 * member_operation_suggest is a Abstract Class containing function 
 * to add member through suggestion
 *
 * @author piyush
 */
abstract class member_operation_suggest {
    
    
    function add_son_suggest($name, $gender, $id) {
        global $db, $user;

        //fill array with data
        $finalarray = array('name' => $name, 'gender' => $gender, 'id' => $id);

        //Put it in database
        //echo "insert into suggested_info (typesuggest,suggested_value,suggested_by,ts) values('child', '" .
        //  json_encode($finalarray) . "'," . $_COOKIE['id'] . "," . time();

        return $db->query("insert into suggested_info (typesuggest,suggested_value,suggested_by,ts) values('child', '" .
                json_encode($finalarray) . "'," . $user->user['id'] . "," . time() . ")");
    }

    function remove_suggest($id) {
        global $db, $user;

        $query = $db->query("insert into suggested_info (typesuggest,suggested_value,suggested_by,ts) values
            ('remove', '$id'," . $user->user['id'] . "," . time() . ")");
        return $query;
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

        return $db->query("insert into suggested_info (typesuggest,suggested_value,suggested_by,ts) values
            ('edit', '" . json_encode($finalarray) . "'," . $user->user['id'] . "," . time() . ")");
    }
}

?>
