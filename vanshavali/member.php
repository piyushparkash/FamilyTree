<?php

/**
 * This class is used to manipulate user data
 * @extends member_operation
 * @author piyush
 */
require_once 'member_operation.php';

class member extends member_operation {

    /**
     * The constructor of the class
     * @param integer $memberid The ID of the member
     * @return null
     */
    public function __construct($memberid) {
        parent::__construct($memberid);
        $this->populate_data($memberid);
        $this->autofix();
    }

    /**
     * Return true if the user is male
     * @return boolean
     */
    function ismale() {
        if ($this->data['gender'] == MALE) {
            return TRUE;
        } else {
            return false;
        }
    }

    /**
     * Returns true if the user is female
     * @return boolean
     */
    function isfemale() {
        if ($this->data['gender'] == FEMALE) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * This function is used to get the ID of the parent of the
     * current logged in user
     * @return \member
     */
    function getFather() {
        return new member($this->data['sonof']);
    }

    /**
     * 
     * @global \db $db
     * @return \member
     */
    function getMother() {
        global $db;
        $query = $db->get("select related_to from member where id = " . $this->data['sonof']);

        return new member($query['related_to']);
    }

    /**
     * This function is used to get the children of the currenyl logged-in user
     * @global \db $db The instance of the db class 
     * @return array Array of \member
     */
    function get_sons() {
        global $db;
        $finalarray = array();
        $query = $db->query("select * from member where sonof=$this->id");
        while ($row = $db->fetch($query)) {
            array_push($finalarray, new member($row['id']));
        }
        return $finalarray;
    }

    /**
     * This function is used to check if the user has children
     * This function also return the number of children of the user
     * @global \db $db The instance of the \db class
     * @return integer
     */
    function has_sons() {
        global $db;
        $query = $db->query("select count(*) as nosons from member where sonof=$this->id");
        $row = $db->fetch($query);
        return $row['nosons'];
    }

    /**
     * This function is used to fix any anamolies found in member data
     * such as if user has children but the relationshipstatus is set to Single 
     * @global \db $db
     * @return null
     */
    function autofix() {

        $nosons = $this->has_sons();
        if ($nosons > 0) {
// If the member has sons Change the status to married
// Add a wife and add parents to wife and create a new family
            $this->set_relationship(MARRIED);
            $this->addwife();

//Call the second autofix function to fix the wife and 
//husband of same family issue.
            $this->autofix2();
        }
    }

    function autofix2() {
//check which records were overwritten
        $logfile = fopen("logfile.txt", "w+");
        global $vanshavali;
//This check should only run for wifes
        $spouse = new member($this->data['related_to']);

//Check if it is wife
        if ($spouse->isfemale()) {
//Now check if we have different family for the wife here
            if ($spouse->data['family_id'] == $this->data['family_id']) {
                $new_family_id = $vanshavali->addfamily($spouse->data['membername']);
                if ($new_family_id == false) {
                    fwrite($logfile, "Could not add a new family");
                    fclose($logfile);
                    return;
                }
                $spouse->set("family_id", $new_family_id);
                fwrite($logfile, "$spouse->id--corrected" . "\n");
//And we are done.
            }
        }
        fclose($logfile);
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
        if (!$db->query("update member set relationship_status=$relationship_id where id=$this->id")) {
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
        if ($db->query("update member set related_to=$related_to where id=" . $this->data['id'])) {
            $this->set_relationship(MARRIED);

            return true;
        } else {
            return false;
        }
    }

    /**
     * Return the gender code of the member
     * 1 = Female
     * 0= Male
     * @return integer
     */
    function gender() {
        return $this->data['gender'];
    }

    /**
     * 
     * @global \db $db
     * @param type $propertyName
     * @return type
     */
    function get($propertyName) {
        global $db;

        $query = $db->query("select $propertyName from member where id = " . $this->id);

        $row = $db->fetch($query);

        return $row[$propertyName];
    }

    /**
     * 
     * @global \db $db
     * @param type $propertyName
     * @param type $value
     * @return type
     */
    function set($propertyName, $value) {
        global $db;

        $query = $db->query("update member set $propertyName = '$value' where id = " . $this->id);

        return $query;
    }
    
    /**
     * 
     * @return boolean|\member
     */
    function spouse() {
        if ($this->data['relationship_status'] == MARRIED) {
            return new member($this->data['related_to']);
        } else {
            return false;
        }
    }
    
    function isAdmin()
    {
        return $this->data['admin'];
    }

}
