<?php

/**
 * This class handles Member Entity
 * To be Only used by other classes
 * @extends member_operation_suggest
 * @author piyush
 */
require 'member_operation_suggest.php';

abstract class member_operation extends member_operation_suggest {

    /**
     *
     * @var integer The ID of the member
     */
    public $id;

    /**
     *
     * @var array This array contains all information about the user
     */
    public $data;

    /**
     * Constructor of the class
     * @param integer $memberid The ID of the member
     * @return null
     */
    public function __construct($memberid) {
        $this->id = $memberid;
    }

    /**
     * This function is used to fill the $data variable with member data
     * @global \db $db The instance of the \db class
     * @param integer $memberid The ID of the member
     * @return null
     */
    function populate_data($memberid) {
        // Fill user variable with user data
        global $db;
        $query = $db->query("Select * from member where id=$memberid");
        $row = $db->fetch($query);
        $this->data = $row;

        //Adding a check for the name. This is when user forgets to add name in the suggestion.
        $row['membername'] = trim($row['membername']) == "" ? "unknown" : $row['membername'];
    }

    /**
     * @TODO: Change name of the function. Misleading it is as it also works for daughters.
     * 
     * This function is used to add a child of the member. Returns false on error
     * @global \db $db The instance of db class
     * @param string $name The name of the new member
     * @param integer $gender The gender of the new member
     * @param boolean $suggest If this is a suggestion then set this to true
     * @return integer The ID of the new member just added
     */
    function add_son($name, $gender, $suggest = false) {
        if ($suggest) {
            if (intval($this->data['gender']) == MALE) {

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

                /* @var $familyid integer */
                $familyid = $this->data['family_id'];
                if (empty($familyid)) {

                    //If family id is not defined than assume that he/she belongs to the default family
                    $familyid = 1;
                }


                //Prepare the sql according to the gender
                $sql = "";
                if (intval($this->data['gender']) === FEMALE) {
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

    /**
     * This function is used to check if check if the member has a spouse or not
     * Returns true If the member has spouse else returns false
     * @global \db $db The instance of the db class
     * @return boolean
     */
    function hasspouse() {
        global $db;

        $row = $db->get("select related_to from member where id=" . $this->id);

        if (!empty($row['related_to'])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * This function is used to add wife to the member. Returns true if
     * successfull operation else returns false
     * @global \vanshavali $vanshavali The instance of the \vanshavali class
     * @global \db $db The instance of the \db class
     * @param string $name The name of Wife
     * @param boolean $suggest Set to true if this is a suggestion
     * @return boolean
     */
    function addwife($name = "Wife", $suggest = false) {
        global $vanshavali;
        if ($suggest) {
            return parent::addwife_suggest($name, $this->id);
        } else {

            //Firstly to check if the member already has a wife
            if (!$this->hasspouse()) {
                //Add wife directly in the database
                $father_family_id = $vanshavali->addfamily($name);
                $mother_family_id = $vanshavali->addfamily($name);
                if ($father_family_id && $mother_family_id) {
                    // Now add parents with that family id
                    $fatherid = $vanshavali->addmember_explicit("Father", MALE, $father_family_id);
                    $motherid = $vanshavali->addmember_explicit("Mother", FEMALE, $mother_family_id);

                    $father = $vanshavali->getmember($fatherid);
                    $mother = $vanshavali->getmember($motherid);

                    $mother->related_to($fatherid);
                    $father->related_to($motherid);

                    $wife = new member($father->add_son("Wife", FEMALE));
                    $this->related_to($wife->id);
                    $this->set_relationship(MARRIED);
                    $wife->related_to($this->id);
                    $wife->set_relationship(MARRIED);
                    return true;
                } else {
                    return false;
                }
            }
        }
    }

    /**
     * This function is used to add husband to the member. It returns true on 
     * successfull operation.
     * @global \vanshavali $vanshavali Instance of the \vanshavali class
     * @global \db $db Instance of the \db class
     * @param string $name The name of the husband
     * @param boolean $suggest Set to true if is a suggestion
     * @return boolean
     */
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

    /**
     * This function is used to remove the member from the database
     * Returns true on successfull operation else false
     * @global \db $db Instance of the \db class
     * @param boolean $suggest Set to true if is a suggestion
     * @return boolean
     */
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

    /**
     * This function is used to edit a user details. Returns true on successful
     * operation else returns false
     * @global \db $db
     * @param string $name The new name of the member
     * @param integer $gender The new gender of the member. See Below.
     * @param integer $relationship The relationship status of the member See Below.
     * @param integer $dob The Timestamp of the DOB of the member
     * @param Integer $alive The living status of the member
     * @param boolean $suggest Set to true if this is a suggest
     * @return boolean
     * 
     * Gender
     * 0 == Male
     * 1 == Female
     * 
     * Relationship Status
     * 0 == Single
     * 1 == Married
     * 
     * Alive
     * 0 == Deceased
     * 1 == Living
     */
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
