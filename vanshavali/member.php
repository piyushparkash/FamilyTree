<?php

/**
 * This class is used to manipulate user data
 * @extends member_operation
 * @author piyush
 */
require_once __DIR__ . '/member_operation.php';

class member extends member_operation {

    /**
     * The constructor of the class
     * @param integer $memberid The ID of the member
     * @return null
     */
    public function __construct($memberid) {
        parent::__construct($memberid);
        $this->populate_data($memberid);
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
        return $this->data[$propertyName];
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

    /**
     * 
     * @return boolean
     */
    function isAdmin() {
        return $this->data['admin'];
    }

    function sendForgotPassword() {
        global $vanshavali;

        //generate the Url
        $url = $_SERVER['SERVER_NAME'] . "/forgotpassword.php?token=" . $this->data['tokenforact'];

        //Get the values to send.
        return $vanshavali->mail("mail.forgotpassword.tpl", [
                    "url" => $url,
                    "membername" => $this->data['membername']
                        ], $this->data['emailid'], "Forgot Password?");
    }
    
    function changePassword($newPassword)
    {
        //Update the password of the currect user
        return $this->set("password", md5($newPassword));
    }

}
