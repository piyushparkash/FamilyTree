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
    
    function addmember_explicit($membername,$gender,$familyid)
    {
        global $db;
        if ($db->query("insert into member (membername,gender,family_id) values ('$membername',$gender,$familyid)"))
        {
            return mysql_insert_id();
        }
        else
        {
            return false;
        }
    }
    
    function addfamily($name)
    {
        global $db;
        if ($db->query("insert into family (family_name,ts) values('$name\'s Family'," . time() . ")"))
        {
            return mysql_insert_id();
        }
        else
        {
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

}

?>
