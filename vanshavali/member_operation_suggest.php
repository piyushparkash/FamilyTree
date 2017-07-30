<?php

/**
 * This class is used to add suggestion of member modification
 *
 * @author piyush
 */
abstract class member_operation_suggest {

    /**
     * This function is used to add suggestion about addition of a child.
     *
     * Returns true on successfull operation else returns false
     *
     * @global \db $db The instance of the \db class
     * @global \user $user Instanec of the \user class
     * @param string $name The name of the member to be added
     * @param integer $gender The gender of the member
     * @param integer $id The ID of the member whose child addition suggestion is being added
     * @return boolean
     */
    function addChild_suggest($name, $gender, $id) {
        global $db, $user, $suggest_handler;

        //Add the suggestion
        return $suggest_handler->add_suggest(ADDMEMBER, $id, array(NAME => $name, GENDER => $gender));
    }

    /**
     * This function is used to add a suggestion of removal of a member from FamilyTree
     *
     * Returns true on successfull run else false
     * @global \db $db Instance of the db class
     * @global \user $user Instance of the user class
     * @param Integer $id The ID of the suggestion
     * @return boolean
     */
    function remove_suggest($id) {
        global $db, $user, $suggest_handler;

        //We need the removed members father name to show
        //firstly accquire the current member details

        $current = vanshavali::getmember($id);
        $father = vanshavali::getmember($current->data['sonof']);

        return $suggest_handler->add_suggest(DELMEMBER, $id, $father->data['membername']);
    }

    /**
     * This function is used to add suggestion regarding modification of user
     * detail modification. Returns false on error.
     * @global \db $db Instance of the db class
     * @global \user $user Instance of the user class
     * @param string $name The new name of the member
     * @param integer $gender The new gender of the member
     * @param integer $relationship The new relationship status of the member
     * @param integer $dob The new DOB Timestamp
     * @param integer $alive The new alive ID
     * @param integer $id The ID of the member to be edited
     * @return boolean
     */
    function edit_suggest($name, $gender, $relationship, $dob, $alive, $gaon, $id) {
        global $db, $user, $suggest_handler;

        //Check if dob has some value or not
        if (!empty($dob)) {

            preg_match("/([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4,4})/", $dob, $matches);
            $dob = mktime(0, 0, 0, $matches[2], $matches[1], $matches[3]);
        }


        return $suggest_handler->add_suggest(NAME, $id, $name) &&
                $suggest_handler->add_suggest(GENDER, $id, $gender) &&
                $suggest_handler->add_suggest(RELATIONSHIP, $id, $relationship) &&
                $suggest_handler->add_suggest(DOB, $id, $dob) &&
                $suggest_handler->add_suggest(GAON, $id, $gaon) &&
                $suggest_handler->add_suggest(ALIVE, $id, $alive);

    }

    /**
     * This function is used to add new wife suggestion. Returns false on error
     * @global \db $db Instance of db class
     * @global \user $user Instance of the user class
     * @param string $name The name of the new wife
     * @param integer $id The ID of the member whose wife is to be added
     * @return boolean
     */
    function addSpouse_suggest($name, $gender, $id) {
        global $db, $user, $suggest_handler;

        return $suggest_handler->add_suggest(ADDSPOUSE, $id, array(NAME => $name, GENDER => $gender));
    }

    function addParents_suggest($fathername, $mothername, $id) {
        global $suggest_handler;

        if (!in_array($gender, array(MALE, FEMALE))) {
            return false;
        }

            return $suggest_handler->add_suggest(ADDPARENTS, $id, array(
                "fathername" => $fathername,
                "mothername" => $mothername
            ));
    }

    function removeParents_suggest($id)
    {
        global $suggest_handler;

        return $suggest_handler->add_suggest(REMOVEPARENTS, $id);
    }

    function removeSpouse_suggest($id)
    {
        global $suggest_handler;
        return $suggest_handler->add_suggest(REMOVESPOUSE, $id);
    }

}

?>
