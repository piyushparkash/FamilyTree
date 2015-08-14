<?php

/**
 * This the core Class of the Family Tree and contains function to manage it.
 *
 * @author piyush
 */
require_once 'member.php';
require_once 'constants.php';

class vanshavali {

    /**
     * 
     * Constructor of the class
     */
    public function __construct() {
        
    }

    public function getHeadofFamily($familyid) {
        $query = $db->query("select id from member where sonof is null and dontshow=0 and gender=" . MALE . " and family_id=$familyid");
        $row = $db->fetch($query);

        return $row['id'];
    }

    public function distanceFromTop($member, $samefamily = true) {


        //While we are going up. We will first go to mother and then we will
        // to the father. This way we will be able to calculate relations for
        // female members of the family too.

        if (!$samefamily) {
            //Since the family is not same. We will first switch to husband
            $member = $this->getmember($member->data['related_to']);
        } //else we continue with normal execution

        $distance = 0;
        $mother = true;
        while (1) {

            if ($mother) {
                //Get father
                //echo "\nget the father to get mother. Father id = " . $member->data['sonof'];
                $member = $this->getmember($member->data['sonof']);

                if ($member === false) {
//                    echo "\nWent into first part break. Couldn't get the above given father";
                    break;
                }

                //Get mother through him
                $member = $this->getmember($member->data['related_to']);
//                echo " \nhere we should get the mother. Mother id = " . $member->id;

                $mother = false; //next turn is for father

                $distance++;
            } else {

                //Check for root. Last person will be male
                if ($member == false or $member->data["sonof"] == null) {
//                    echo "\nPrevious loop didn't return any member or there is no father to this member = " . $member->data['sonof'];
                    break;
                }
                //Previous loop was for mother. This one would be for father
                $member = $this->getmember($member->data['related_to']);

                if ($member == false) {
//                    echo " we can't find a husband to this wife.\n";
                    break;
                }

                $mother = true; //Next up is mother

                $distance++;
            }
        }

        if (!$samefamily) {
            //Since we switched to husband in the starting of the function,
            //We are by default one level down. Lets add to it.
            $distance++;
        }

        return $distance;
    }

    public function memberDistance($to, $from) {
        $sameFamily = false;
        $sameFather = false;
        $diffsex = false;


        //Check if they are from same family.
        if ($to->data['family_id'] == $from->data['family_id']) {
            $sameFamily = true;
        }

        //Check if they child of same father
        if ($to->data['sonof'] == $from->data['sonof']) {
            $sameFather = true;
        }

        //Check if they are of same gender
        if ($to->ismale() == $from->isfemale()) {
            $diffsex = true;
        }

        //Check if given member is mother of first member
        if ($from->getMother()->id == $to->id) {
            $is_mother = true;
        }

        //Check if given member is father of second member
        $father = $from->getparent();
        if ($father->id == $to->id) {
            $is_father = true;
        }

        $levelDistance = $this->distanceFromTop($from) - $this->distanceFromTop($to, $sameFamily);

        return array("is_mother" => $is_mother,
            "is_father" => $is_father,
            "sameFamily" => $sameFamily,
            "sameFather" => $sameFather,
            "diffsex" => $diffsex,
            "levelDistance" => $levelDistance
        );
    }

    private $relation_array = array(
        array(false, false, true, true, false, 0, "Brother", 0) //brother
    );

    private function comparerelationArray($array) {
        //Initialize all the parameters
        $is_mother = $is_father = $sameFamily = $sameFather = $diffsex = $levelDistance = false;
        $result = -1;

        //Now compare this array with all options that we have
        foreach ($this->relation_array as $key => $singlerelation) {
            $is_mother = ($singlerelation[0] == $array['is_mother']);
            $is_father = ($singlerelation[1] == $array['is_father']);
            $sameFamily = ($singlerelation[2] == $array['sameFamily']);
            $sameFather = ($singlerelation[3] == $array['sameFather']);
            $diffsex = ($singlerelation[4] == $array['diffsex']);
            $levelDistance = ($singlerelation[5] == $array['levelDistance']);

            if ($is_mother && $is_father && $sameFamily && $sameFather && $diffsex && $levelDistance) {
                $result = array($singlerelation[6], $singlerelation[7]);
                break;
            }
        }

        if ($result == -1) {
            return false; // We couldn't find such relation
        }
        /* @var $result array|number */ else if (is_array($result)) {
            return $result;
        }
    }

    /**
     * 
     * @param type $from
     * @param type $to
     * @return string|boolean
     * 
     * Constants
     * 0 brothers
     * 1 mother
     * 2 father
     * 3 uncle chacha
     * 4 aunty chachi
     * 5 sister
     * 6 grand father
     * 7 grand mother
     * 8 fufa (male)
     * 9 fufa (female)
     * 10 mama
     * 11 mami
     * 12 brother cousin
     * 13 sister cousin
     * 14 bhanja
     * 15 bhanji
     * 16 granchild
     * 17 bhatiji
     * 18 bhatija
     * 19 Fore father
     * 20 fore mother
     * 21 grand son
     * 22 grand daughter
     * 23 bahoo
     * 24 bahoo cousin
     * 25 nata
     * 26 nati
     * 
     */
    public function calculateRelation($from, $to) {
        if ($from === $to) {
            return false;
        }

        if ($from == null or $to == null) {
            return false;
        }

        $from = $this->getmember($from);
        $to = $this->getmember($to);

        //Get the parameters between them
        $relationparam = $this->memberDistance($to, $from);

        $result = $this->comparerelationArray($relationparam);

        if (is_array($result)) {
            return $result;
        } else {
            return print_r($relationparam); //Just for development purpose
//            return "Cannot determine relation";
        }
    }

    /**
     * 
     * @param type $who
     * @param type $whom
     * @return boolean
     */
    public function hasAccess($who, $whom) {

        //accessArray
        $accessArray = array(); //This contains the relation which have access over each other
        //Basic things. User can edit is own information.
        if ($who === $whom) {
            return true;
        }

        $relation = calculateRelation($who, $whom);

        if (in_array($relation, $accessArray)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 
     * @global \db $db
     * @param type $member
     * @return type
     */
    public function makeAdmin($member) {
        global $db;

        $query = $db->query("Update member set admin = 1 where id = $member");

        return $query;
    }

    /**
     * 
     * @global \db $db
     * @return boolean
     */
    public function firstTimeFamily() {
        global $db;

        //Get the count on the number of members
        $query = $db->query("select count(*) membercount from family;");

        $count = $db->fetch($query);

        if ($count['membercount'] > 0) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * 
     * @global \db $db
     * @return boolean
     */
    public function firstTime() {
        global $db;

        //Get the count on the number of members
        $query = $db->query("select count(*) membercount from member;");

        $count = $db->fetch($query);

        if ($count['membercount'] > 0) {
            return false;
        } else {
            return true;
        }
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

        //Before doing anything. Lets check if we have everything
        if (empty($id)) {
            return false;
        }

        global $db;
        $query = $db->query("select * from member where id=$id");
        $ret = $db->fetch($query);

        //Check if we have such member or not
        if ($ret == false) {
            return false;
        }

        //else proceed with normal execution
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
        global $user;

        $obj = array();
        $obj['id'] = $row["id"];
        $obj['name'] = trim($row['membername']) == "" ? "unknown" : $row["membername"];
        $obj['data'] = array(
            "dob" => ($row['dob'] ? strftime("%d/%m/%Y", $row['dob']) : ""),
            "relationship_status" => ($row['relationship_status'] == 0 ? "Single" :
                    "Married"),
            "relationship_status_id" => $row['relationship_status'],
            "alive" => ($row['alive'] == 0 ? "Deceased" : "Living"),
            "gender" => $row['gender'],
            "alive_id" => $row['alive'],
            'image' => empty($row['profilepic']) ? "common.png" : $row['profilepic'],
            'familyid' => $row['family_id']
//            'relation' => ($this->calculateRelation($row["id"], $user->user["id"]) ? $user->is_authenticated() : "Login to view relation")
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
