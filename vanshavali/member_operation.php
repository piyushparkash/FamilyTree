<?php

/**
 * Contains functions to permanently add, remove or edit Member
 * To be Only used by other classes
 *
 * @author piyush
 */
require 'member_operation_suggest.php';

abstract class member_operation extends member_operation_suggest {

    public $id;

    public function __construct($memberid) {
        $this->id = $memberid;
    }

    function add_son($name, $gender, $suggest = false) {
        if ($suggest) {
            return parent::add_son_suggest($name, $gender, $this->data['id']);
        } else {
            //Add son directly to the Member database
            global $db;

            //Prepare the sql
            $sql = "Insert into member(membername,gender,sonof) values('$name',$gender," . $this->data['id'] . ")";

            //Execute the sql
            if (!$db->get($sql)) {
                trigger_error("Cannot add member. Error executing the query");
                return false;
            }
        }
    }

    function remove($suggest = false) {
        if ($suggest) {
            return parent::remove_suggest($this->data['id']);
        } else {
            //Remove the member completely
            global $db;

            //Prepare the sql
            if (!$db->get("Update member set dontshow=1 where id=" . $this->data['id'])) {
                trigger_error("Cannot delete member. Error Executing the query");
                return false;
            }
        }
    }

    function edit($name, $gender, $relationship, $dob, $alive, $suggest = FALSE) {
        if ($suggest) {
            return parent::edit_suggest($name, $gender, $relationship, $dob, $alive, $this->data['id']);
        } else {
            //Change the details directly...
            global $db;

            //Prepare the sql and execute it...
            if (!$db->get("Update member set membername='$name',gender=$gender,
                relationship_status=$relationship,dob=$dob, alive=$alive where id=" . $this->data['id'])) {
                trigger_error("Error Editing member. Error Executing query");
                return FALSE;
            }
        }
    }

}

?>
