<?php

/**
 * Class that manipulates Members
 *
 * @author piyush
 */
require_once 'suggest.php';

class member extends suggest {

    public $data;

    public function __construct($memberid) {
        $this->populate_data($memberid);
    }

    function getparent() {
        return new member($this->data['sonof']);
    }

    function get_sons() {
        global $db;
        $finalarray = array();
        $query = $db->query("select * from member where sonof=$this->id");
        while ($row = $db->fetch($query)) {
            array_push($finalarray, new member($row['id']));
        }
        return $finalarray;
    }

    function has_sons() {
        global $db;
        $query = $db->query("select count(*) as nosons from member where sonof=$this->id");
        $row = $db->fetch($query);
        return $row['nosons'];
    }

    function populate_data($memberid) {
        // Fill user variable with user data
        global $db;
        $query = $db->query("Select * from member where id=$memberid");
        $row = $db->fetch($query);
        $this->data = $row;
    }

    function add_son($name, $gender, $suggest) {
        if ($suggest) {
            parent::add_son_suggest($name, $gender, $this->data['id']);
        } else {
            //Add son directly to the Member database
            global $db;

            //Prepare the sql
            $sql = "Insert into member(membername,gender,sonof) values('$name',$gender," . $this->data['id'] . ")";

            //Execute the sql
            if (!$db->get($sql)) {
                trigger_error("Cannot add member. Error executing the query",E_USER_ERROR);
            }
        }
    }

    function remove($suggest) {
        if ($suggest) {
            parent::remove_suggest($this->data['id']);
        } else {
            //Remove the member completely
            global $db;

            //Prepare the sql
            if (!$db->get("Update member set dontshow=1 where id=" . $this->data['id'])) {
                trigger_error("Cannot delete member. Error Executing the query",E_USER_ERROR);
            }
        }
    }

    function edit($name, $gender, $relationship, $dob, $alive, $suggest) {
        if ($suggest) {
            parent::edit_suggest($name, $gender, $relationship, $dob, $alive, $this->data['id']);
        } else {
            //Change the details directly...
            global $db;
            
            //Prepare the sql and execute it...
            if (!$db->get("Update member set membername='$name',gender=$gender,
                relationship_status=$relationship,dob=$dob, alive=$alive where id=".$this->data['id']))
            {
                trigger_error("Error Editing member. Error Executing query", E_USER_ERROR);
            }
        }
    }

}

?>
