<?php

/**
 * @author Piyush
 * @copyright 2012
 * getdata.php used to get certain data from database
 */
require "header.php";
require "vanshavali/suggest.php";
global $db;

//get the type of data to be extracted
switch ($_REQUEST['action']) {

    //when to check whether a given username already exists or not
    case "username_check":
        $username = $_POST['username'];
        $query = $db->query("select count(*) as no from member where username='$username'");
        $row = $db->fetch($query);

        //if count is >1 then there are user with that username
        if ($row['no'] > 0) {
            echo json_encode("Username already taken");
        } else {
            echo json_encode("true");
        }
        break;

    //When to get suggestions for approval
    case "getsuggestions":
        global $suggest_handler;
        $suggest_handler->getsuggestions();
        break;


    //When adding wife
    case "operation_addwife":
        global $vanshavali;

        //Get the member to be changed
        $member = $vanshavali->getmember($_POST['husband']);

        //Add wife to the member
        if ($member->addwife($_POST['name'], TRUE)) {
            ajaxSuccess();
        } else {
            ajaxError();
        }
        break;


    //When adding husband
    case "operation_addhusband":
        global $vanshavali;

        //Get the member to be changed
        $member = $vanshavali->getmember($_POST['wife']);

        //Add wife to the member
        if ($member->addhusband($_POST['name'], TRUE)) {
            ajaxSuccess();
        } else {
            ajaxError();
        }
        break;


    //When to approve suggestions
    case "suggestionapproval":
        //Retreive the values
        $suggest_id = $_POST['suggestid'];
        $action = $_POST['suggest_action'];

        $suggest = new suggest($suggest_id);
        switch ($action) {
            case 0:
                //Reject the suggestion
                if (!$suggest->reject()) {
                    trigger_error("Cannot reject suggestion. Error Executing query", E_USER_NOTICE);
                } else {

                    //Pass the id of the suggest so that proper HTML element can be made disappear
                    ajaxSuccess(array("suggestid" => $suggest_id));
                }
                break;
            case 1:
                //Accept the suggestion
                if (!$suggest->approve()) {
                    trigger_error("Cannot approved the Suggestion. Error Executing query", E_USER_NOTICE);
                } else {
                    //Pass the id of the suggest so that proper HTML element can be made disappear
                    ajaxSuccess(array("suggestid" => $suggest_id));
                }

                break;
            case 2:
                //Mark the suggestion as don't know
                if (!$suggest->dontknow()) {
                    trigger_error("Cannot reject suggestion. Error Executing query", E_USER_NOTICE);
                } else {

                    //Pass the id of the suggest so that proper HMTL element can be made disappear
                    ajaxSuccess(array('suggestid' => $suggest_id));
                }
                break;
        }
        break;
    case "operation_add":
        global $vanshavali;

        //Variables should have some value
        if (isset($_POST['name']) && isset($_POST['gender']) && isset($_POST['sonof'])) {

            //get the member to be modified
            $member = $vanshavali->getmember($_POST['sonof']);

            //add son suggestion to it
            if (!$member->add_son($_POST['name'], $_POST['gender'], TRUE)) {
                trigger_error("Cannot add member. Some error Occured.");
            } else {
                ajaxSuccess();
            }
        }
        break;

    case "operation_remove":
        global $vanshavali;
        if (!empty($_POST['memberid']) && !empty($_POST['type'])) {
            //get the member to be deleted
            $member = $vanshavali->getmember($_POST['memberid']);

            //add removal suggestion
            if (!$member->remove(TRUE)) {
                trigger_error("Cannot add member. Some error occured");
            } else {
                ajaxSuccess();
            }
        }
        break;
    case "operation_edit":
        global $vanshavali;
        if (isset($_POST['type']) && isset($_POST['name']) && isset($_POST['gender']) && isset($_POST['relationship']) && isset($_POST['dob']) && isset($_POST['alive']) && isset($_POST['memberid'])) {
            //Get the member
            $member = $vanshavali->getmember($_POST['memberid']);

            //Now add the suggestion
            if (!$member->edit($_POST['name'], $_POST['gender'], $_POST['relationship'], $_POST['dob'], $_POST['alive'], TRUE)) {
                trigger_error("Cannot Edit Member. Some error occured");
            } else {
                ajaxSuccess();
            }
        }
        break;

    case "feedback":
        $name = $_POST['name'];
        $email = $_POST['email'];
        $message = $_POST['message'];
        if (!$db->query("insert into feedback (user_name,user_emailid,feedback_text) 
                values ('$name','$email','$message')")) {
            trigger_error("Some error occured");
        } else {
            ajaxSuccess();
        }
        break;
    case "checkregistered":
        global $db, $vanshavali;
        $res = $vanshavali->getmember($_POST['id']);
        if (empty($res->data['username']) && empty($res->data['password'])) {
            ajaxSuccess();
        } else {
            ajaxError();
        }
        break;


    default: //when nothing matches
        break;
}
?>
