<?php

/**
 * @author Piyush
 * @copyright 2012
 * getdata.php used to get certain data from database
 */
//connect to database
require "header.php";
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

    case "operation_add":
        global $vanshavali;
        
        //Variables should have some value
        if (!empty($_POST['name']) && !empty($_POST['gender']) && !empty($_POST['sonof'])) {
            
            //get the member to be modified
            $member = $vanshavali->getmember($_POST['sonof']);
            
            //add son suggestion to it
            $member->add_son($_POST['name'],$_POST['gender'],$_POST['sonof']);
        }
        break;
        
        case "operation_remove":
            global $vanshavali;
            if (!empty($_POST['memberid']) && !empty($_POST['type']))
            {
                //get the member to be deleted
                $member=$vanshavali->getmember($_POST['memberid']);
                
                //add removal suggestion
                $member->remove();
            }
            break;
    default: //when nothing matches
        break;
}
?>