<?php

/**
 * This the core Class of the Family Tree and contains function to manage it.
 *
 * @author piyush
 */
require_once 'member.php';

class vanshavali {

    /**
     * 
     * Constructor of the class
     */
    public function __construct() {
        
    }

    /**
     * This special function is used to add member to the Tree
     * without using any of the member class
     * Returns the ID of the new member else false if any error occurred
     * @global \db $db Instance of the db class
     * @param string $membername The name of the new member to be added
     * @param integer $gender The gender of the new member
     * @param integer $familyid The Family ID of the new member
     * @return integer ID of the new member created
     */
    function addmember_explicit($membername, $gender, $familyid) {
        global $db;
        if ($db->query("insert into member (membername,gender,family_id) values ('$membername',$gender,$familyid)")) {
            return mysql_insert_id();
        } else {
            return false;
        }
    }

    /**
     * This function is used to add family in the Tree.
     * Returns the ID of the new family created or returns false if any error
     * occured
     * @global \db $db Instance of db class
     * @param string $name The name of the Family
     * @return integer ID of the new Family
     */
    function addfamily($name) {
        global $db;
        if ($db->query("insert into family (family_name,ts) values('$name Family'," . time() . ")")) {
            return mysql_insert_id();
        } else {
            return false;
        }
    }

    /**
     * This function is used get the details about a member.
     * @global \db $db Instance of the db class
     * @param integer $id ID of the member whose details is to be fetched
     * @return \member
     */
    function getmember($id) {
        global $db;
        $query = $db->query("select * from member where id=$id");
        $ret = $db->fetch($query);
        $member = new member($ret['id']);
        return $member;
    }

    /**
     * This function is used to register a user in Family Tree. Returns false on
     * error.
     * @global \db $db Instance of db class
     * @global \user $user Instance of user class
     * @param array $details Array containing details about the new member
     * @return boolean
     */
    function register($details) {
        global $db, $user;
        
        //convert the password to md5 hash
        $details[1] = md5($details[1]);
        
        //The token for activation
        $token = $user->generate_token();

        //Sql Statement
        if (!empty($details[8])) { //If member is not already connected to Family Tree then insert else update
            $sql = "update member set membername='$details[9]',username='$details[0]',password='$details[1]',dob=$details[2],gender=$details[3],relationship_status=$details[4],gaon='$details[5]',
	emailid='$details[6]',alive=1,aboutme='$details[7]',joined=" . time() . ",tokenforact='$token' where id=$details[8]";
        } else {
            $sql = "insert into member (membername,username,password,dob,gender,relationship_status,gaon,emailid,alive,aboutme,joined,tokenforact)
                values('$details[9]','$details[0]','$details[1]',$details[2],$details[3],$details[4],'$details[5]','$details[6]',1,'$details[7]'," . time() . ",'$token')";
        }
        //Finally execute the sql
        $ret = $db->query($sql);

        //Mail Options
        $mail_options = array(
            'username' => $details[0],
            'email' => $details[6],
            'not_connected' => !empty($details[8]) ? true : false
        );
        if ($ret != false) {
            $this->mail("mail.register.confirm.tpl", $mail_options, $details[6], 'Welcome to Vanshavali | Email Confirmation');
            return true;
        } else {
            trigger_error("Cannot Connect to the database. Please try again by refreshing the page", E_USER_ERROR);
            return false;
        }
    }

    /**
     * This function is used to send a mail to an emailID. Returns false on error
     * @global \template $template Instance of template class
     * @param string $template_name The name of the mail template to be used in the mail
     * @param array $data The variables needed by the template used in the mail
     * @param string $to EmailID of the Recipent
     * @param string $subject The subject of the Mail
     * @return boolean
     */
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

    /**
     * This function is used to used to create structure to used by JIT. It takes
     * input the array of a row from member table and converts it into the JIT 
     * structure.
     * @param array $row
     * @return array
     */
    function createstruct($row) {
        $obj = array();
        $obj['id'] = $row["id"];
        $obj['name'] = $row['membername'];
        $obj['data'] = array(
            "dob" => ($row['dob'] ? strftime("%d/%m/%Y", $row['dob']) : ""),
            "relationship_status" => ($row['relationship_status'] == 0 ? "Single" :
                    "Married"),
            "relationship_status_id" => $row['relationship_status'],
            "alive" => ($row['alive'] == 0 ? "Deceased" : "Living"),
            "gender" => $row['gender'],
            "alive_id" => $row['alive'],
            'image' => empty($row['profilepic']) ? "common.png" : $row['profilepic'],
            'familyid' => $row['family_id'],
            'gaon' => $row['gaon']
        );
        return $obj;
    }

    /**
     * This function is used to get the child of the given member to be fetched 
     * to the JIT. It fetches the details of the given member from the database
     * and uses createstruct() to convert it into JIT structure.
     * @global \db $db Instance of the db class
     * @param integer $id Ihe of the member whose children are to be fetched
     * @return array
     */
    function getchild($id) {
        global $db;
        $finalarray = array();
        $query = $db->query("select * from member where sonof=$id and dontshow=0");
        while ($row = $db->fetch($query)) {
            $obj = $this->createstruct($row);
            $obj['children'] = $this->getwife($row['id']);
            array_push($finalarray, $obj);
        }
        return $finalarray;
    }

    /**
     * This function is used to get the wife of the given member to be fetched
     * into JIT. It fetches the details of the given member from the database
     * and uses createstruct() to convert it into JIT structure.
     * @global \db $db Instance of db class
     * @param integer $id ID of the member whose wife is to be fetched
     * @return array|null
     */
    function getwife($id) {
        global $db;
        $finalarray = array();
        $row = $db->get("select * from member where id in (select related_to from member where id=$id)");
        $obj = array();
        // Space Tree Object if he has a wife
        if ($row) {
            $obj = $this->createstruct($row);
            $obj['children'] = $this->getchild($id);
            array_push($finalarray, $obj);
            return $finalarray;
        } else {
            return NULL;
        }
    }

    
    
    
    //The following function are not working and are to be improved ********
    /**
     * This function is used to get the members in JSON format to be used
     * with the JIT
     * @global \db $db Instance of db class
     * @param integer $familyid The ID of the family whose member are to be shown
     * By default members of Family 1 are shown
     * @return array|boolean
     */
    function getJson_new($familyid = 1) {

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

    /**
     * This function is used to generate JSON structure to be used in JIT of all
     * children and subchildren under the passed member
     * @global \db $db Instance of the db class
     * @param integer $id ID of the member whose children are to be fetched
     * @return array
     */
    function getchild_new($id) {
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

    /**
     * This function is used to generate JSON structure to be used in JIT of the
     * wife of the passed member
     * @global \db $db Instance of db class
     * @param integer $id ID of the member whose wife is to be fetched
     * @return array|null
     */
    function getwife_new($id) {
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
