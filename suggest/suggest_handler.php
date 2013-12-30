<?php

/**
 * This package will be used to handle the suggest system
 * @author Piyush
 */
/**
 * This array will hold all types of suggestions
 */
$suggests = array();

require_once 'suggest_storage.php';

class suggest_handler {

    public function __construct() {
        
    }

    public function getviewname($detail) {

        global $user, $vanshavali, $template;
//Find the structure of the suggest
        $struct = $this->find_structure($detail['typesuggest']);

//Now do check here if we have the structure
//because if not then the program will crash
//Collect the data needed
//Here is the needed data
//from , to , old_value, newvalue, sod

        $finalarray['from'] = $vanshavali->getmember($user->user['id']);
        $finalarray['to'] = $vanshavali->getmember($detail['suggested_to']);
        $finalarray['oldvalue'] = $detail['old_value'];
        $finalarray['newvalue'] = $detail['newvalue'];

//Check if we have all the data that needs to be passed
        $error = false;
        foreach ($struct->parameter as $value) {
            if (!isset($finalarray[$value])) {
                $error = TRUE;
            }
        }
//get the template content, We haven't passed any data into it. So check here
        if ($error) {
            trigger_error("Not enough parameters to show the suggestion: $detail[1]", E_USER_ERROR);
            return false;
        } else {
            $template->assign($finalarray);
            $result = $template->fetch($struct->tpl);

            return $result;
        }
    }

    public function getsuggestions() {
        global $db, $user, $template;

        //Make the query
        $query = $db->query("select * from suggested_info where approved=0 and id not in 
            (select suggest_id from suggest_approved where user_id=" . $user->user['id'] . ")");

        //Now prepare the data to be shown
        while ($row = $db->fetch($query)) {
            echo $this->getviewname($row);
        }
    }

    /**
     * This method is to be used to register a new suggest type
     * @param type $name The name of the suggest
     * @param type $tpl The tpl to be used while showing user the suggest
     * @param type $parameters Any parameters required by the suggest
     * @return boolean Return true if successfully registered
     */
    public function register_handler($name, $tpl, $parameter, $type) {
        global $suggests;
        if (empty($name) || empty($tpl) || empty($parameter) || empty($type)) {
// Here raise a serious error and working will be interrupted if
// the given suggest is not registered
            trigger_error("$name suggest not registered correctly. Please check", E_USER_ERROR);
            return false;
        }
// Store all the information of the suggest
        $suggests[] = new suggest_storage($name, $tpl, $parameter);
    }

    /**
     * 
     * @global db $db
     * @global vanshavali $vanshavali
     * @param string $name
     * @param string $old_value
     * @param string $new_value
     * @param int $to
     */
    public function add_suggest($name, $to, $new_value = NULL) {
        global $db, $vanshavali;

        //To return at the end
        $success = true;
        // First find the parameters and structure of the given suggest
        $suggest_structure = $this->find_structure($name);

        if (!$suggest_structure) {
            trigger_error("Wrong Suggestion Name Passed. Please check.", E_USER_ERROR);
        }

        //The suggest structure is not simple in this case we have three types of suggest
        // ie add/remove/modify. Find out the type of the suggest
        $suggesttype = $suggest_structure->type;

        //Now use switch to do execution according to the type
        switch ($suggesttype) {
            case ADD:
            case DEL:
                //Now in this case we don't have any old value or new value
                //So the newvalue and the old value field remains empty in this case
                //We don't have to find any old value. So lets implement
                if (!$db->query("insert into suggested_info (typesuggest, newvalue, oldvalue, from, to, ts) values('$name', '$new_value', '$old_value', " . $user->user['id'] . ", $to, " . time() . ")")) {
                    $success = false;
                }
                break;
            case MODIFY:
                //Now in this case there always will be a new value and a old value. So nothing is empty
                //So lets find the old value
                $query = $db->fetch($db->query("select $name from member where id=$to"));

                $old_value = $query[$name]; // And we have the old value now lets add the suggest
                if (!$db->query("insert into suggested_info (typesuggest, newvalue, oldvalue, from, to, ts) values('$name', '$new_value', '$old_value', " . $user->user['id'] . ", $to, " . time() . ")")) {
                    $success = false;
                }
                break;
        }

        return $success;
    }

    /**
     * 
     * @param string $name
     * @return boolean|suggest_storage
     */
    public function find_structure($name) {
        global $suggests;
        $found_key = NULL;
        foreach ($suggests as $key => $value) {
            if ($value->name == $name) {
                $found_key = $key;
            }
        }
        if ($found_key != null) {
            return $suggests[$found_key];
        } else {
            return false;
        }
    }

}

;