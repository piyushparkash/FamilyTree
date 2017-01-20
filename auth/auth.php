<?php

/**
 * This class is used to create and destroy sessions. It also handles User Authentication
 * @param none
 * @author piyush
 */
require_once __DIR__ . '/OAuthHandler.php';

class auth {
    //public $oauth;

    /**
     * The constructor of the class
     * @param null
     */
    public function __construct() {
        session_start();
    }

    public function setConsumerToken($consumerkey, $consumersecret, $endpoint, $namespace) {

        $this->oauth = new OAuthHandler($consumerkey, $consumersecret, $endpoint, $namespace);
    }

    public function wp_login_init() {
        //Initiate the oauth process
        //Authorize...
        if (!$this->oauth->init_auth_process())
            return false;
        return true;
    }
    
    public function authenticate_wp($authverifier) {
        //Second phase of oauth

        $accesstoken = $this->oauth->init_access_process($authverifier, $redirect);

        if (!$accesstoken) {
            return false;
        }

        //Get the user details of wordpress user
        $wp_user = $this->oauth->auth_fetch("users/me");

        if (!$wp_user) {
            return false;
        }

        return array($accesstoken, $wp_user);
    }

    /**
     * This function is used to basically log-in the user.
     * It takes username and password combination as parameter and return true
     * if such user is present and authentication process was successfully
     * completed. If no such user is present it returns false
     * @global \db $db The instance of the the db class
     * @global array $_SESSION The global Session variable storing all the session data
     * @param string $username The username of the users to check against
     * @param string $password The password of the user
     * @return boolean
     * 
     */
    public function authenticate($username, $password) {
        global $db;

        //Convert Password in md5 Hash
        $password = md5($password);
        $query = $db->query("select * from member where username='$username' and password='$password'");
        $row = $db->fetch($query);

        //if no row found or if user's account is not activated yet then
        if ($row == false) {
            return false;
        }

        //Check here for approval if user registration has been approved by the admin. Currently disabled
        //Start the session and start storing data about user
        global $_SESSION;
        $token = $this->generate_token($row['id']);
        $_SESSION['membername'] = $row['membername'];
        $_SESSION['id'] = $row['id'];
        $_SESSION['token'] = $token;
        $_SESSION['authenticated'] = true;

        //if everything is alright then return true
        return true;
    }


    public function authenticate_thr_wp($wordpresID) {
        //Get the corresponding user id for the wordpress id
        global $db;

        $query = $db->query("select * from member where wordpress_user=$wordpresID");
        $row = mysqli_fetch_array($query);

        global $_SESSION;
        if (is_null($row))
        {
            //Just set the wordpress user details
            $_SESSION['wpid'] = $wordpresID;
            $_SESSION['authenticated'] = true;
            return true;
        }
        
        //else if we have FamilyTree Details also then that also
        $token = $this->generate_token($row['id']);
        $_SESSION['membername'] = $row['membername'];
        $_SESSION['id'] = $row['id'];
        $_SESSION['token'] = $token;
        $_SESSION['authenticated'] = true;
    }

    /**
     * This function is used to check if user's account has been approved by the admin
     * @param type $id The ID of the user to check for
     * @return boolean
     */
    function user_account_activated($id) {

        $data = $this->get_user($id);
        if ($data['approved'] == 0) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * This function returns all the information of the user in array just as it is stored in the table
     * @global \db $db The instance of the db class
     * @param type $id The ID of the user for which the information is to be fetched
     * @return array
     */
    function get_user($id) {
        global $db;
        $row = $db->fetch($db->query("select * from member where id=$id"));
        return $row;
    }

    /**
     * This function is used to check if sessions was initialized i.e. all 
     * the required cookies are set. Return true if set 
     * @global array $_SESSION The Superglobal Session variable
     * @return boolean
     */
    function check_session() {
        global $_SESSION;
        if (isset($_SESSION['membername'], $_SESSION['id'], $_SESSION['token'], $_SESSION['authenticated'])) {
            if ($_SESSION['authenticated'] == true) {
                if ($this->check_token($_SESSION['id'], $_SESSION['token'])) {
                    return true;
                }
            }
        }
        //When we have wordpress enabled
        else if (isset($_SESSION['wpid'], $_SESSION['authenticated']))
        {
            return true;
        }

        return false;
    }

    /**
     * This function is used to checkt the token generated against the user.
     * Return true if matches
     * @param integer $id The ID of the user
     * @param string $token The token generated for the user
     * @return boolean
     */
    function check_token($id, $token) {
        $numtoken = preg_replace("/[^0-9]/", "", $token);
        $numtoken = (int) $numtoken;
        if ($numtoken == $id) {

            return true;
        } else {

            return false;
        }
    }

    /**
     * This function is used to check if the user is logged-in
     * Returns true if logged-in
     * @return boolean
     */
    function is_authenticated() {
        if ($this->check_token($_SESSION['id'], $_SESSION['token']) && $_SESSION['authenticated']) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * This function is generated token for a user.
     * Token is a random alphanumeric string
     * @param integer $id The ID of the used to generate the token for.
     * @return string The generated Token
     */
    function generate_token($id = '') {

        $codelenght = 20;
        for ($newcode = "", $newcode_length = 0; $newcode_length < $codelenght; $newcode_length++) {
            if ($newcode_length == 5) {
                $newcode = $newcode . $id;
            }
            $x = 1;
            $y = 2;
            $part = rand($x, $y);
            if ($part == 1) {
                $a = 65;
                $b = 90;
            } // UpperCase
            if ($part == 2) {
                $a = 97;
                $b = 122;
            } // LowerCase
            $newcode = $newcode . chr(rand($a, $b));
        }
        return $newcode;
    }

    /**
     * This function is used to destroy the session created
     * @return null
     */
    function destroy_session() {
        session_unset();
    }

    /**
     * This function is used to log-out the user
     * @global \db $db The instance of the db class
     * @return boolean
     */
    function unauthenticate() {
        global $db;
        if (!$this->is_authenticated()) {
            return false;
        }
        //Update the last login timestamp
        if ($db->query("update member set lastlogin=" . time() . " where id=" . $_SESSION['id'])) {

            //Unset all the session values
            $this->destroy_session();
            if ($this->is_authenticated()) {
                trigger_error("Error Ending Session", E_USER_ERROR);
                return false;
            }
        }
        return true;
    }

}

?>
