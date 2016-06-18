<?php

/**
 * Suggestion Class is used to add, remove or edit suggestions
 * Basically a Class to operate on suggested_info and suggest_approval table
 * @extends member_operation_suggest
 * @author piyush
 */
require_once __DIR__ . '/member_operation_suggest.php';

class suggest extends member_operation_suggest {

    public $id, $suggested_value, $typesuggest, $suggestedby;

    /**
     * Constructor of the class. This gathers the basic information about
     * the suggestion which is to be managed
     * @global \db $db Instance of the db class
     * @param integer $suggestid The ID of the suggestion to be managed
     */
    function __construct($suggestid) {
        global $db;
        $this->id = $suggestid;
        $row = $db->get("select * from suggested_info where id=$suggestid");
        //$this->suggested_value = json_decode($row['suggested_value'], TRUE);
        $this->typesuggest = $row['typesuggest'];
        $this->suggestedby = $row['suggested_by'];

        //if typesuggest is remove then suggested value is in json else not
        if (in_array($row['typesuggest'], array(DEL, ADD))) {
            $this->suggested_value = $row['new_value'];
        } else {
            $this->suggested_value = json_decode($row['new_value'], true);
        }
    }

    /**
     * This function is used to add approval to this suggestion. Returns false
     * on error
     * @global \db $db Instance of the db class
     * @global \user $user Instance of the user class
     * @return boolean
     */
    function approve() {
        global $db, $user;
        if (!$db->get("Insert into suggest_approved(suggest_id,user_id,action) values($this->id, 
                " . $user->user['id'] . ",1)")) {
            return false;
        }

        //Check if suggestion has crossed 50% Mark
        $this->check_decision();
        return true;
    }

    /**
     * This function is used to add rejection to the suggestion. Return false
     * on error
     * @global \db $db Instance of the db class
     * @global \user $user Instance of user class
     * @return boolean
     */
    function reject() {
        //Rejects the $id provided in the constructor
        global $db, $user;
        if (!$db->get("Insert into suggest_approved (suggest_id,user_id,action) values
            ($this->id," . $user->user[0] . ",0)")) {
            return false;
        }

        //Check if suggestion has crossed 50% mark
        $this->check_decision();
        return TRUE;
    }

    /**
     * This function is used to mark a suggestion as don't know. Returns false
     * on error
     * @global \db $db Instance of the db class
     * @global \user $user Instance of the user class
     * @return boolean
     */
    function dontknow() {
        //Marks suggestion as don'tknow
        global $db, $user;
        if (!$db->get("Insert into suggest_approved (suggest_id,user_id,action)
            values($this->id," . $user->user[0] . ",2)")) {
            return false;
        }

        //Check if suggestion has crossed 50% mark
        $this->check_decision();
        return true;
    }

    /**
     * This function is to check the percentage of the approval/rejection/dontknow
     * of this suggestion. 
     * @global \db $db Instance of the db class
     * @return array 
     */
    public function checkpercent() {
        global $db;

        //Get all Rejections, Approvals, Dontknow's
        $query = $db->query("select * from suggest_approved where suggest_id=" . $this->id);
        $row2 = $db->get('select count(*) as totaluser from member where username!="" and password!=""');
        $total = (float) $row2['totaluser'];
        $noapproved = 0.0;
        $norejected = 0.0;
        $nodontknow = 0.0;

        //Count the no of approvals/Rejections
        while ($row = $db->fetch($query)) {
            switch (intval($row['action'])) {
                case 0:$norejected++;
                    break;
                case 1:$noapproved++;
                    break;
                case 2:$nodontknow++;
                    break;
                default:
                    break;
            }
        }
        $noapproved = (float) ($noapproved / $total) * 100;
        $nodontknow = (float) ($nodontknow / $total) * 100;
        $norejected = (float) ($norejected / $total) * 100;

        //If approved>50 then accept the suggestion
        //if rejected>50 then reject the suggestion
        //if donknow>50 then even i don't know what to do

        return array($noapproved, $norejected, $nodontknow);
    }

    /**
     * This function is used to take a decision whether to approve the suggestion
     * , reject it on the basis percentage of approval or rejection
     * @return null
     */
    private function check_decision() {
        $percent = $this->checkpercent();

        //3rd has the boolean which checks if everyone has voted
        if ($percent[3]) {
            if ($percent[0] > 50) {
                //Almost half the people have agreed, So lets add it permanently..
                $this->apply();
            } else if ($percent[1] > 50) {
                //More than half of the people have rejected it, So lets remove the suggestion
                $this->apply();
            } else if ($percent[2] > 50) {
                //More than half of the people don't know about it
                //So we have no choice lets approve this suggestion
                $this->apply();
            }
        }
    }

    /**
     * This function is used to accept the suggestion and apply the changes to the 
     * main member table
     * @global \vanshavali $vanshavali Instance of the vanshavali class
     * @global \db $db Instance of the db class
     * @return null
     */
    private function apply() {
        global $vanshavali, $db, $suggest_handler;

        //Check if suggested_value was JSON or not
        if (is_array($this->suggested_value)) {
            $member = $vanshavali->getmember($this->suggested_value['id']);
        } else {
            $member = $vanshavali->getmember($this->suggested_value);
        }

        //Get the sub type of suggest to be passed below
        $struct = $suggest_handler->find_structure($this->typesuggest);


        //We have the member to be edited. Now apply the given operation
        switch ($struct->type) {
            case ADD:
                $member->add_son($this->suggested_value['name'], $this->suggested_value['gender']);
                break;
            case DEL:
                $member->remove();
                break;
            case MODIFY:
                $member->edit($this->suggested_value['name'], $this->suggested_value['gender'], $this->suggested_value['relationship'], $this->suggested_value['dob'], $this->suggested_value['alive']);
                break;
        }

        //Now delete all the suggestion approvals as they are of no use
        //$this->approval_delete();
        //Now mark the suggestion as applied So that it can be used in future
        $db->get("update suggested_info set approved=1 where id=$this->id");
    }

    /**
     * This function is used to delete all the data regarding the suggestion approval
     * or rejection. This is to be used when the suggestion is applied and user votes
     * are of no use. Although it is automatically invoked.
     * @global \db $db
     * @return boolean
     */
    function approval_delete() {
        global $db;

        if ($db->get("Delete from suggest_approved where suggest_id=$this->id")) {
            return TRUE;
        } else {
            return false;
        }
    }

}

?>
