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
    public $data;

    public function __construct($memberid) {
        $this->id = $memberid;
    }

    function populate_data($memberid) {
        // Fill user variable with user data
        global $db;
        $query = $db->query("Select * from member where id=$memberid");
        $row = $db->fetch($query);
        $this->data = $row;
    }

    function add_son($name, $gender, $suggest = false) {
        if ($suggest) {
            if (intval($this->data['gender']) == 0) {

                //If a male member then send his id
                return parent::add_son_suggest($name, $gender, $this->data['id']);
            } else {

                //If not a male member then send the id of the spouse
                return parent::add_son_suggest($name, $gender, $this->data['related_to']);
            }
        } else {

            //Before doing all this check if the member has a wife
            if ($this->hasspouse()) {

                //Add son directly to the Member database
                global $db;

                //Get the familyid of the parent
                $familyid = $this->data['family_id'];
                if (empty($familyid)) {

                    //If family id is not defined than assume that he/she belongs to the default family
                    $familyid = 1;
                }


                //Prepare the sql according to the gender
                $sql = "";
                if (intval($this->data['gender']) == 1) {
                    $sql = "Insert into member(membername,gender,sonof,family_id) 
                values('$name',$gender," . $this->data['related_to'] . ",$familyid)";
                } else {
                    $sql = "Insert into member(membername,gender,sonof,family_id) 
                values('$name',$gender," . $this->data['id'] . ",$familyid)";
                }

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

    function hasspouse() {
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

            //Firstly to check if the member already has a wife
            if (!$this->hasspouse()) {
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
    }

    function addhusband($name = "Husband", $suggest = false) {
        global $vanshavali, $db;
        if ($suggest) {
            return parent::addhusband_suggest($name, $this->id);
        } else {
            //Add wife directly in the database
            $family_id = $vanshavali->addfamily($name . "'s Family");
            if ($family_id) {
                // Now add parents with that family id
                $fatherid = $vanshavali->addmember_explicit("Father", 0, $family_id);
                $motherid = $vanshavali->addmember_explicit("Mother", 1, $family_id);

                $father = $vanshavali->getmember($fatherid);
                $mother = $vanshavali->getmember($motherid);

                $mother->related_to($fatherid);
                $father->related_to($motherid);

                $wife = new member($father->add_son("Wife", 0));
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
