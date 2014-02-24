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
     * Checks if the member is married or not
     * @return boolean
     */
    public function ismarried() {
        if ($this->data['relationship_status'] == 1 and !empty($this->data['related_to'])) {
            return true;
        } else {
            return FALSE;
        }
    }

    public function spouse() {
        if ($this->ismarried()) {
            return new member($this->data['related_to']);
        } else {
            return false;
        }
    }

    public function family() {
        return $this->data['family_id'];
    }

    public function removefamily() {
        //Remove whole of the family
        global $db;
        if ($this->family() != 1) {
            //We have to remove all the constraints first
            if ($db->query("update member set related_to=null where family_id=" . $this->family())) {

                if ($db->query("delete from member where family_id=" . $this->family())) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
    }

    public function removespouse() {
        //Remove the wife
        global $db;

        //Fetch the spouse and as they are not connected we can remove all of the family
        //as because of them the families were connected except for family 1
        if ($this->ismarried()) {
            $spouse = $this->spouse();

            //Check if it belongs to family 1
            if ($spouse->family() == 1) {
                $spouse->removeme();
                return; // We cannot remove the main Family
            } else {
                $spouse->removefamily();
                unset($spouse);
                $this->related_to(null);
                $this->set_relationship(0);
            }
        }
    }

    /**
     * Return true if the user is male
     * @return boolean
     */
    function ismale() {
        if ($this->data['gender'] == 0) {
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
        if ($this->data['gender'] == 1) {
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
    function getparent() {
        return new member($this->data['sonof']);
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
        global $db;
        $nosons = $this->has_sons();
        if ($nosons > 0) {
            // If the member has sons Change the status to married
            // Add a wife and add parents to wife and create a new family
            $this->set_relationship(1);
            $this->addwife();
        }
    }

    public function makesingle() {

        return $this->set_relationship(0) && $this->set_relationship(0);
    }

    public function removeme() {
        global $db;

        //Check if the spouse family id is different
        $spouse = $this->spouse();

        if ($spouse) {
            if ($spouse->family() != $this->family()) {
                //Ok we are disconnected and now we can erase the entire family
                //Disconnect both of them
                $this->makesingle();
                $spouse->makesingle();

                //Now remove all the members
                $this->removefamily();
                return true;
            }
        }



        if ($db->query("delete from member where id = " . $this->data['id'])) {
            return true;
        } else {
            return false;
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
        if (is_null($related_to)) {
            $related_to = "NULL";
        }
        if ($db->query("update member set related_to=$related_to where id=" . $this->data['id'])) {
            if ($related_to != "NULL") {
                $this->set_relationship(1);
            }
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

}

?>
