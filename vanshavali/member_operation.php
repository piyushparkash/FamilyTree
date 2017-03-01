<?php

/**
 * This class handles Member Entity
 * To be Only used by other classes
 * @extends member_operation_suggest
 * @author piyush
 */
require __DIR__ . '/member_operation_suggest.php';

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
    function addChild($name, $gender, $suggest = false) {
        global $user;

        //Check for member to member access
        $hasAccess = vanshavali::hasAccess($user->user['id'], $this->id);

        if ($suggest & !$hasAccess) {
            if (intval($this->data['gender']) == MALE) {

                //If a male member then send his id
                return parent::addChild_suggest($name, $gender, $this->data['id']);
            } else {

                //If not a male member then send the id of the spouse
                return parent::addChild_suggest($name, $gender, $this->data['related_to']);
            }
        } else {

            //Before doing all this check if the member has a wife
            if ($this->hasspouse()) {

                //Add son directly to the Member database
                global $db;

                //Check whether is father or mother
                If ($this->data['gender'] == FEMALE)
                {
                    //Get the family id of father
                    $father = vanshavali::getmember($this->data['related_to']);
                    $familyID = $father->data['family_id'];
                }
                else
                {
                    //It is the father, get $this family id
                    $familyid = $this->data['family_id'];
                }
                if (empty($familyid)) {

                    //If family id is not defined than assume that he/she belongs to the default family
                    trigger_error("Empty Family. Don't belong to any Family", E_USER_ERROR);
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
                return $db->last_id();
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
        global $user;

        //Check for member to member access
        $hasAccess = vanshavali::hasAccess($user->user['id'], $this->id);

        if ($suggest && !$hasAccess) {
            return parent::addwife_suggest($name, $this->id);
        } else {

            //Firstly to check if the member already has a wife
            if (!$this->hasspouse()) {
                //Add wife directly in the database
                $father_family_id = vanshavali::addfamily($name);
                $mother_family_id = vanshavali::addfamily($name);
                if ($father_family_id && $mother_family_id) {
                    // Now add parents with that family id
                    $fatherid = vanshavali::addmember_explicit("Father", MALE, $father_family_id);
                    $motherid = vanshavali::addmember_explicit("Mother", FEMALE, $mother_family_id);

                    $father = vanshavali::getmember($fatherid);
                    $mother = vanshavali::getmember($motherid);

                    $mother->related_to($fatherid);
                    $father->related_to($motherid);

                    $wife = new member($father->addChild($name, FEMALE));
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
        global $user;

        $hasAccess = vanshavali::hasAccess($user->user['id'], $this->id);

        if ($suggest && !$hasAccess) {
            return parent::addhusband_suggest($name, $this->id);
        } else {

            //Add wife directly in the database
            $family_id = vanshavali::addfamily($name);
            if ($family_id) {
                // Now add parents with that family id
                $fatherid = vanshavali::addmember_explicit("Father", MALE, $family_id);
                $motherid = vanshavali::addmember_explicit("Mother", FEMALE, $family_id);

                $father = vanshavali::getmember($fatherid);
                $mother = vanshavali::getmember($motherid);

                $mother->related_to($fatherid);
                $father->related_to($motherid);

                $husband = new member($father->addChild($name, MALE));
                $this->related_to($husband->id);
                $this->set_relationship(MARRIED);
                $husband->related_to($this->id);
                $husband->set_relationship(MARRIED);
                return true;
            } else {
                return false;
            }
        }
    }

    function addSpouse($name = array(MALE => "Husband", FEMALE => "Wife"), $suggest = false)
    {
        global $user;

        $hasAccess = vanshavali::hasAccess($user->user['id'], $this-id);
        if ($suggest && !$hasAccess)
        {
            return parent::addSpouse_suggest($name, $this->id);
        }
        else
        {
            //Check if the member already has a spouse
            if ($this->hasspouse())
            {
                return false;
            }
            else
            {
                //Add the spouse in the database
                
                //Add family for the spouse
                $familyid = vanshavali::addfamily($name);
                if ($familyid)
                {
                    //Add parents of the spouse
                    $fatherid = vanshavali::addmember_explicit("Father", MALE, $familyid);
                }
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

        global $user;

        $hasAccess = vanshavali::hasAccess($user->user['id'], $this->id);
        if ($suggest && !$hasAccess) {
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

        //If reached here, then the operations is complete
        return true;
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
        global $user;

        $hasAccess = vanshavali::hasAccess($user->user['id'], $this->id);

        if ($suggest && !$hasAccess) {
            return parent::edit_suggest($name, $gender, $relationship, $dob, $alive, $this->data['id']);
        } else {
            //Change the details directly...
            global $db;
            
            //Check if any of the variables is empty
            $name = empty($name) ? "NULL" : $name;
            $gender = empty($gender) ? "NULL" : $gender;
            $relationship = empty($relationship) ? "NULL" : $relationship;
            $dob = empty($dob) ? "NULL" : $dob;
            $alive = empty($alive) ? "NULL" : $alive;

            //Prepare the sql and execute it...
            if (!$db->get("Update member set membername='$name',gender=$gender,
                relationship_status=$relationship,dob=$dob, alive=$alive where id=" . $this->data['id'])) {
                trigger_error("Error Editing member. Error Executing query");
                return FALSE;
            }
        }

        //If reached till here, then the operation is complete
        return True;
    }

    function addParent($fathername, $mothername, $suggest = FALSE)
    {
        $hasAccess = vanshavali::hasAccess($user->user['id'], $this-id);

        if ($suggest && !$hasAccess)
        {
          return parent::addParent_suggest($name, $gender, $this->data['id']);
        } else {
          {
            global $db;

            $fatherid = vanshavali::addmember_explicit($fathername, MALE, $this->data['family_id']);
                $motherid = vanshavali::addmember_explicit($mothername, FEMALE, $this->data['family_id']);

                $father = vanshavali::getmember($fatherid);
                $mother = vanshavali::getmember($motherid);

                $mother->related_to($fatherid);
                $father->related_to($motherid);

            //Set this member sonof to fatherID
            $this->set("sonof", $fatherid);

          }
        }

    }

        /**
     * This function is used to set the relationship status of the current user
     * Returns true if successfull else false
     * @global \db $db The instance of the \db class
     * @param integer $relationship_id The relationship ID. See Below.
     * @return boolean
     * 
     * Relationship ID
     * 0 == Single
     * 1 == Married
     */
    function set_relationship($relationship_id) {
        global $db;
        if (!$this->set("relationship_status", $relationship_id)) {
            return false;
        } else {
            return true;
        }
    }

        /**
     * This function is add wife of the member. Returns true if successful
     * else false. The member to be added as wife should already be created
     * @global \db $db The instance of the db class
     * @param integer $related_to The ID of the member to be added as wife
     * @return boolean
     */
    function related_to($related_to) {
        global $db;
        if ($this->set("related_to", $related_to)) {
            $this->set_relationship(MARRIED);

            return true;
        } else {
            return false;
        }
    }

    /**
     * This function is used to set value of specific column for the given member
     * @global \db $db
     * @param type $propertyName The name of the column to update
     * @param type $value The new value of the column to be updated
     * @return type Returns true or false based on db transaction
     */
    function set($propertyName, $value) {
        global $db;

        $value = $db->real_escape_string($value);

        $query = $db->query("update member set $propertyName = '$value' where id = " . $this->id);

        return $query;
    }

}
