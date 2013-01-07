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

        if ($db->query("insert into suggested_info (typesuggest,suggested_value,suggested_by,ts) values('child', '" .
                        json_encode($finalarray) . "'," . $user->user['id'] . "," . time() . ")"))
        {
            //Put the approval suggestion of user that has created the suggestion
            return ($db->query("insert into suggest_approved (suggest_id,user_id,action) values(".mysql_insert_id().",
                ".$user->user['id'].",1)"));
        }
        else
        {
            return false;
        }
        
    }

    function remove_suggest($id) {
        global $db, $user;

        if ($db->query("insert into suggested_info (typesuggest,suggested_value,suggested_by,ts) values
            ('remove', '$id'," . $user->user['id'] . "," . time() . ")"))
        {
            return ($db->query("insert into suggest_approved (suggest_id,user_id,action) values(".mysql_insert_id().",
                ".$user->user['id'].",1)"));
        }
        else
        {
            return false;
        }
        
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

        if ($db->query("insert into suggested_info (typesuggest,suggested_value,suggested_by,ts) values
            ('edit', '" . json_encode($finalarray) . "'," . $user->user['id'] . "," . time() . ")"))
        {
            return ($db->query("insert into suggest_approved (suggest_id,user_id,action) values(".mysql_insert_id().",
                ".$user->user['id'].",1)"));
        }
        else
        {
            return false;
        }
    }

}

?>
