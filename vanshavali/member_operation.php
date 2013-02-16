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

            //Before doing all this check if the member has a wife
            if ($this->haswife()) {
                //Add son directly to the Member database
                global $db;
                //Get the familyid of the parent
                $query = $db->get("select family_id from member where id=" . $this->id);
                $familyid = $query['family_id'];
                if (empty($familyid)) {
                    $familyid = 1;
                }


                //Prepare the sql
                $sql = "Insert into member(membername,gender,sonof,family_id) 
                values('$name',$gender," . $this->data['id'] . ",$familyid)";

                //Execute the sql
                if (!$db->get($sql)) {
                    trigger_error("Cannot add member. Error executing the query");
                    return false;
                }
                return mysql_insert_id();
            } else {
                return false;
            }
        }
    }

    function haswife() {
        global $db;

        $row = $db->get("select related_to from member where id=" . $this->id);

        if (!empty($row['related_to'])) {
            return true;
        } else {
            return false;
        }
    }

    function addwife($name = "Wife", $suggest = false) {
        global $vanshavali, $db;
        if ($suggest) {
            return parent::addwife_suggest($name, $this->id);
        } else {
            //Add wife directly in the database
            $family_id = $vanshavali->addfamily($name);
            if ($family_id) {
                // Now add parents with that family id
                $fatherid = $vanshavali->addmember_explicit("Father", 0, $family_id);
                $motherid = $vanshavali->addmember_explicit("Mother", 1, $family_id);

                $father = $vanshavali->getmember($fatherid);
                $mother = $vanshavali->getmember($motherid);

                $mother->related_to($fatherid);
                $father->related_to($motherid);

                $wife = new member($father->add_son("Wife", 1));
                $this->related_to($wife->id);
                $this->set_relationship(1);
                $wife->related_to($this->id);
                $wife->set_relationship(1);
                return true;
            } else {
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
