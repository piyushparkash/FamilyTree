<?php

/**
 * This the core Class of the Family Tree and contains function to manage it.
 *
 * @author piyush
 */
require_once __DIR__ . '/member.php';
require_once __DIR__ . '/../constants.php';

class vanshavali {

    /**
     *
     * Constructor of the class
     */
    public function __construct() {
        
    }

    public $admin_email, $wp_login, $hostname;

    public function getHeadofFamily($family_id = null) {
        global $db;
        if (is_null($family_id)) {
            $query = $db->query("select * from member where sonof is null and dontshow = 0 and family_id in (select family_id from member where admin = 1)");
        } else {
            $query = $db->query("select id from member where sonof is null and dontshow=0 and gender=" . MALE . " and family_id=$family_id");
        }

        $row = $db->fetch($query);
        return $row['id'];
    }

    /**
     *
     * @param type $member
     * @param type $samefamily
     * @return int
     */
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
                if ($member == false) {
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
        $is_parent = false;
        $is_spouse = false;
        $is_child = false;


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

        //Check if the given member is the wife of the from member
        $spouse = $from->spouse();
        if ($spouse->id == $to->id) {
            $is_spouse = true;
        }

        //Check if the second member is the parent of the first member
        $mother = $from->getMother();
        $father = $from->getFather();
        if ($to->id == $mother->id or $to->id == $father->id) {
            $is_parent = true;
        }

        //Check if the given member is the child of respective member
        $children = $from->get_sons();
        foreach ($children as $child) {
            if ($child->id == $to->id) {
                $is_child = true;
            }
        }


        $levelDistance = $this->distanceFromTop($from) - $this->distanceFromTop($to, $sameFamily);

        return array("is_child" => $is_child,
            "is_parent" => $is_parent,
            "is_spouse" => $is_spouse,
            "gender" => $from->gender(),
            "sameFamily" => $sameFamily,
            "sameFather" => $sameFather,
            "diffsex" => $diffsex,
            "levelDistance" => $levelDistance
        );
    }

    private $relation_array = array(
        array(false, false, true, MALE, false, false, true, -1, "Wife", 12),
        array(false, false, true, FEMALE, false, false, true, -1, "Husband", 13),
        array(false, false, false, FEMALE, false, false, true, -1, "Brother-in-law (Devar)", 14),
        array(true, false, false, null, true, false, false, -2, "Son", 15),
        array(true, false, false, null, true, false, true, -2, "Daughter", 16),
        array(false, false, false, null, true, true, True, 0, "Brother", 0),
        array(false, false, false, null, true, true, false, 0, "Sister", 17),
        array(false, true, false, null, false, false, null, 1, "Mother", 1),
        array(false, true, false, null, true, false, null, 2, "Father", 2),
        array(false, false, false, null, true, false, false, 2, "Chacha (Uncle)", 3),
        array(false, false, false, null, false, false, true, 1, "Chachi (Aunt)", 4),
        array(false, false, false, null, true, false, true, 0, "Cousin Sister", 5),
        array(false, false, false, null, true, false, false, 0, "Cousin Brother", 6),
        array(false, false, false, null, false, false, true, -1, "Bhabhi (Sister-in-law)", 7),
        array(false, false, false, null, true, false, true, -2, "Bhatiji (Niece)", 8),
        array(false, false, false, null, true, false, false, -2, "Bhatija (Niece)", 9),
        array(false, false, false, null, false, false, true, 3, "Dadi Maa (GrandMother)", 10),
        array(false, false, false, null, true, false, false, 4, "Dada Ji (GrandFather)", 11),
    );

    /**
     *
     * @param type $array
     * @return boolean
     */
    private function comparerelationArray($array) {
        //Initialize all the parameters
        $is_child = $is_parent = $is_spouse = $gender = $sameFamily = $sameFather = $diffsex = $levelDistance = false;
        $result = array();
        $approx_relation = false;


        //Now compare this array with all options that we have
        foreach ($this->relation_array as $key => $singlerelation) {
            $is_child = ($singlerelation[0] == $array['is_child']);
            $is_parent = ($singlerelation[1] == $array['is_parent']);
            $is_spouse = ($singlerelation[2] == $array['is_spouse']);
            $gender = ($singlerelation[3] == null ? true : ($singlerelation[3] == $array['gender']));
            $sameFamily = ($singlerelation[4] == $array['sameFamily']);
            $sameFather = ($singlerelation[5] == $array['sameFather']);
            $diffsex = ($singlerelation[6] == null ? true : ($singlerelation[6] == $array['diffsex']));
            $levelDistance = ($singlerelation[7] == $array['levelDistance']);

            //Check if this relation was approx relations
            if ($singlerelation[3] == null or $singlerelation[6] == null) {
                $approx_relation = true;
            }

            if ($is_child && $is_parent && $is_spouse && $gender &&
                    $sameFamily && $sameFather && $diffsex && $levelDistance) {

                $result[] = array($singlerelation[8], $singlerelation[9], $approx_relation);
            }

            //Reset the approx_relation variable for the next relation
            $approx_relation = false;
        }


        if (sizeof($result) == 0) {
            return false; // We couldn't find such relation
        }

        //Check here if we have multiple values
        if (sizeof($result) == 1) {
            //Congrats, we heve only on relation to show.
            return $result[0];
        } elseif (sizeof($result) > 1) {
            //We have more than one relation which is matching
            //Select the one which is not approx
            $accurate_relation = -1;
            foreach ($result as $matched_relation) {
                if ($matched_relation[2] == false) { //This says that it is not approximate relation
                    //Get out of the loop
                    $accurate_relation = $matched_relation;
                    break;
                }
            }

            //Check if we have accurate relation
            if ($accurate_relation == -1) {
                return false; //Two many relations matching
            } else {
                return $accurate_relation;
            }
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
        $accessArray = array(12, 13, 15, 16, 0, 17, 1, 2);
        ////This contains the relation which have access over each other
        //Basic things. User can edit is own information.

        if ($who === $whom) {
            return true;
        }

        //Make a check if the person is admin
        $mclass = new member($who);
        if ($mclass->isAdmin()) {
            //The person suggesting is admin. Just do it. :P
            return true;
        }

        $relation = $this->calculateRelation($who, $whom);

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
            return $db->last_id();
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
        $query = $db->query("select id from member where id=$id");
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
        //@todo: wordpress user when not provided will fail
        if (!empty($details[8])) { //If member is not already connected to Family Tree then insert else update
            $sql = "update member set membername='$details[9]',username='$details[0]',password='$details[1]',dob=$details[2],gender=$details[3],relationship_status=$details[4],gaon='$details[5]',
	emailid='$details[6]',alive=1,aboutme='$details[7]',joined=" . time() . ",tokenforact='$token' where id=$details[8]";
        } else {
            $sql = "insert into member (membername,username,password,dob,gender,relationship_status,gaon,emailid,alive,aboutme,joined,tokenforact, family_id)
                values('$details[9]','$details[0]','$details[1]',$details[2],$details[3],$details[4],'$details[5]','$details[6]',1,'$details[7]',"
                    . time() . ",'$token', $details[10])";
        }

        if ($vanshavali->wp_login) {
            if (!empty($details[8])) { //If member is not already connected to Family Tree then insert else update
                $sql = "update member set membername='$details[9]',username='$details[0]',password='$details[1]',dob=$details[2],gender=$details[3],relationship_status=$details[4],gaon='$details[5]',
	emailid='$details[6]',alive=1,aboutme='$details[7]',joined=" . time() . ",tokenforact='$token', wordpress_user=$details[11] where id=$details[8]";
            } else {
                $sql = "insert into member (membername,username,password,dob,gender,relationship_status,gaon,emailid,alive,aboutme,joined,tokenforact, family_id, wordpress_user)
                values('$details[9]','$details[0]','$details[1]',$details[2],$details[3],$details[4],'$details[5]','$details[6]',1,'$details[7]',"
                        . time() . ",'$token', $details[10], $details[11])";
            }
        }
        //Finally execute the sql
        $ret = $db->query($sql);

        //Mail Options
        $mail_options = array(
            'username' => $details[0],
            'email' => $details[6],
            'not_connected' => !empty($details[8]) ? true : false,
            'wp_login' => $vanshavali->wp_login
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
        $user_email = $this->admin_email;

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
     *
     * @param type $templateName
     * @param type $data
     * @param type $subject
     * @return type
     */
    function mailAdmin($templateName, $data, $subject) {

        return $this->mail($templateName, $data, $this->admin_email, $subject);
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
            "gaon" => $row['gaon'],
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
