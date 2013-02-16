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
switch ($_POST['action']) {

    //when to check whether a given username already exists or not
    case "username_check":
        $username = $_POST['username'];
        $query = $db->query("select count(*) as no from member where username='$username'");
        $row = $db->fetch($query);

        //if count is >1 then there are user with that username
        if ($row['no'] > 0) {
            echo json_encode(array("yes" => 1));
        } else {
            echo json_encode(array("yes" => 0));
        }
        break;

    //When to get suggestions for approval
    case "getsuggestions":
        global $user;
        $query = $db->query("select * from suggested_info where approved=0 and id not in 
            (select suggest_id from suggest_approved where user_id=" . $user->user['id'] . ")");
        while ($row = $db->fetch($query)) {
            switch ($row['typesuggest']) {
                //Dialog to be printed if typesuggest is remove
                case "remove":
                    global $template;
                    $template->assign("suggestid", $row['id']);
                    //Get the name of the member to be removed, His father and Member who suggested this...
                    $query2 = $db->query("select * from member where id=" . $row['suggested_by']);
                    $row2 = $db->fetch($query2);
                    $template->assign('suggestedby', $row2['membername']);


                    $query2 = $db->query("select * from member where id=" . $row['suggested_value']);
                    $row2 = $db->fetch($query2);
                    $template->assign("membername", $row2['membername']);

                    //Now the father of the memeber to be removed
                    $query2 = $db->query("select * from member where id=" . $row2['sonof']);
                    $row2 = $db->fetch($query2);
                    $template->assign("fathername", $row2['membername']);
                    $template->display("suggest.confirmremovemember.tpl");
                    break;
                case "child";
                    global $template;
                    $template->assign("suggestid", $row['id']);
                    //Decode the given json value
                    $suggested_value = json_decode($row['suggested_value'], true);

                    //Get the member who suggested it and the father of the new member
                    $query2 = $db->query("select * from member where id=" . $row['suggested_by']);
                    $row2 = $db->fetch($query2);
                    $template->assign("suggestedby", $row2['membername']);

                    $query2 = $db->query("select * from member where id=" . $suggested_value['id']);
                    $row2 = $db->fetch($query2);
                    $template->assign(array('membername' => $suggested_value['name']));

                    $template->assign("fathername", $row2['membername']);
                    
                    //Display son or daughter according to the gender
                    if ($suggested_value['gender']==0)
                    {
                        $template->assign("mnf","son");
                    }
                    else
                    {
                        $template->assign("mnf","daughter");
                    }

                    $template->display('suggest.confirmaddmember.tpl');
                    break;
                case "edit":
                    $template->assign("suggestid", $row['id']);
                    //Decode the suggested information
                    $suggested_value = json_decode($row['suggested_value'], true);

                    //Get the changed member previous values
                    $row2 = $db->get("select * from member where id=" . $suggested_value['id']);

                    //Compare them with each other and set the template values
                    if ($row2['membername'] != $suggested_value['name']) {
                        $template->assign("changed_name", 1);
                    }

                    if ($row2['gender'] != $suggested_value['gender']) {
                        $template->assign("changed_gender", 1);
                    }

                    if ($row2['relationship_status'] != $suggested_value['relationship']) {
                        $template->assign("changed_relationship", 1);
                    }

                    if ($row2['dob'] != $suggested_value['dob']) {
                        $template->assign("changed_dob", 1);
                    }

                    if ($row2['alive'] != $suggested_value['alive']) {
                        $template->assign("changed_alive", 1);
                    }

                    //Constants are all set...now update the values
                    $template->assign(array(
                        "membername" => $row2['membername'],
                        "old_name" => $row2['membername'],
                        "old_relationship" => ($row2['relationship_status'] == 0 ? "Single" : "Married"),
                        "old_dob" => strftime("%d/%b/%G", $row2['dob']),
                        "old_alive" => ($row2['alive'] == 0 ? "No" : "Yes"),
                        "old_gender" => ($row2['gender'] == 0 ? "Male" : "Female"),
                        "new_name" => $suggested_value['name'],
                        "new_relationship" => ($suggested_value['relationship'] == 0 ? "Single" : "Married"),
                        "new_dob" => strftime("%d/%b/%G", $suggested_value['dob']),
                        "new_alive" => ($suggested_value['alive'] == 0 ? "No" : "Yes"),
                        "new_gender" => ($suggested_value['gender'] == 0 ? "Male" : "Female")
                    ));
                    $template->display("suggest.confirmeditmember.tpl");
                    break;
                default :
                    break;
            }
        }
        break;
        
        
        //When adding wife
        case "operation_addwife":
            global $vanshavali;
            
            //Get the member to be changed
            $member=$vanshavali->getmember($_POST['husband']);
            
            //Add wife to the member
            if ($member->addwife($_POST['name'],TRUE))
            {
                ajaxSuccess();
            }
            else
            {
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