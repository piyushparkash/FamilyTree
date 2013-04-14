<?php

/**
 * Core Class
 *
 * @author piyush
 */
require_once 'member.php';

class vanshavali {

    public function __construct() {
        
    }

    function addmember_explicit($membername, $gender, $familyid) {
        global $db;
        if ($db->query("insert into member (membername,gender,family_id) values ('$membername',$gender,$familyid)")) {
            return mysql_insert_id();
        } else {
            return false;
        }
    }

    function addfamily($name) {
        global $db;
        if ($db->query("insert into family (family_name,ts) values('$name Family'," . time() . ")")) {
            return mysql_insert_id();
        } else {
            return false;
        }
    }

    function getmember($id) {
        global $db;
        $query = $db->query("select * from member where id=$id");
        $ret = $db->fetch($query);
        $member = new member($ret['id']);
        return $member;
    }

    function register($details) {
        global $db, $user;

        //The token for activation
        $token = $user->generate_token();

        //Sql Statement
        if (!empty($details[8])) { //If member is not already connected to Family Tree
            $sql = "update member set membername='$details[9]',username='$details[0]',password='$details[1]',dob=$details[2],gender=$details[3],relationship_status=$details[4],gaon='$details[5]',
	emailid='$details[6]',alive=1,aboutme='$details[7]',joined=" . time() . ",tokenforact='$token' where id=$details[8]";
        } else {
            $sql = "insert into member (membername,username,password,dob,gender,relationship_status,gaon,emailid,alive,aboutme,joined,tokenforact)
                values('$details[9]','$details[0]','$details[1]',$details[2],$details[3],$details[4],'$details[5]','$details[6]',1,'$details[7]'," . time() . ",'$token')";
        }
        //Finally execute the sql
        $ret = $db->query($sql);

        if ($ret != false) {
            $this->mail("mail.register.confirm.tpl", array('username' => $details[0], 'token' => $token, 'email' => $details[6]), $details[6], 'Welcome to Vanshavali | Email Confirmation');
            return true;
        } else {
            trigger_error("Cannot Connect to the database. Please try again by refreshing the page", E_USER_ERROR);
            return false;
        }
    }

    function mail($template_name, $data, $to, $subject) {
        global $template;
        //Add Global variable of domain
        $user_email = "me@vanshavali.co.cc";

        //Fetch body from template
        $template->assign($data);
        $body = $template->fetch($template_name);

        //Mail Headers
        $headers = "From: $user_email\r\n";
        $headers .= "Return-Path: $to\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
        $headers .= 'MIME-Version: 1.0' . "\n";
        $headers .= 'Content-type: text/html; UTF-8' . "\r\n";

        return mail($to, $subject, $body, $headers);
    }

    function getJson($familyid = 1) {

        global $db;
        $finalarray = array();
        $query = $db->query("select * from member where sonof is null and dontshow=0 and gender=0");
        //Loop through all the members and feed the row data to a function
        //Loop will filter the data according to the gender and return
        //Keep adding the information to a final array
        $row = $db->fetch($query);

        //Now feed the row to function and in return get the array interface
        if (is_array($row)) {
            $obj = $this->infovisstruct($row);
            //$obj['children'] = $this->getwife($row['id']);

            array_push($finalarray, $obj);
            return $finalarray;
        } else {
            return false;
        }
    }

    function infovisstruct($row) {
        $obj = array();

        if (is_array($row)) {
            // If feed data is array then only do this
            $obj['id'] = $row['id'];
            $obj['name'] = $row['membername'];
            $obj['data'] = array(
                "dob" => ($row['dob'] ? strftime($row['dob'], "%d/%m/%Y") : ""),
                "relationship_status" => ($row['relationship_status'] == 0 ? "Single" :
                        "Married"),
                "relationship_status_id" => $row['relationship_status'],
                "alive" => ($row['alive'] == 0 ? "Deceased" : "Living"),
                "gender" => $row['gender'],
                "alive_id" => $row['alive'],
                'image' => empty($row['profilepic']) ? "common.png" : $row['profilepic']
            );
            $obj['children']=  $this->getwife($row['id']);

            //return the prepared object
            return $obj;
        }
    }

    function getchild($id) {
        global $db;
        $finalarray = array();
        $query = $db->query("select * from member where sonof=$id and dontshow=0");
        while ($row = $db->fetch($query)) {
            $obj = $this->infovisstruct($row);
            $obj['children'] = $this->getwife($row['id']);
            array_push($finalarray, $obj);
        }
        return $finalarray;
    }

    function getwife($id) {
        global $db;
        $finalarray = array();
        $row = $db->get("select * from member where id in (select related_to from member where id=$id)");
        $obj = array();
        // Space Tree Object if he has a wife
        if ($row) {
            $obj = $this->infovisstruct($row);
            $obj['children'] = $this->getchild($id);
            array_push($finalarray, $obj);
            return $finalarray;
        } else {
            return NULL;
        }
    }

}

?>
