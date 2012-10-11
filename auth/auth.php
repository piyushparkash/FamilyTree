<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of auth
 *
 * @author piyush
 */
class auth {

    public function __construct() {
        session_start();
    }

    public function authenticate($username, $password) {
        global $db;
        $query = $db->query("select * from member where username='$username' and password='$password'");
        $row = $db->fetch($query);

        //if no row found or if user's account is not activated yet then
        if ($row == false) {
            return false;
        } elseif ($row['approved']==0) {
            return array('error' => 2);
        }

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

    function user_account_activated($id) {

        $data = $this->get_user($id);
        if ($data['approved'] == 0) {
            return false;
        } else {
            return true;
        }
    }

    function get_user($id) {
        global $db;
        $row = $db->fetch($db->query("select * from member where id=$id"));
        return $row;
    }

    function check_session() {
        global $_SESSION;
        if (isset($_SESSION['membername'], $_SESSION['id'], $_SESSION['token'], $_SESSION['authenticated'])) {
            if ($_SESSION['authenticated'] == true) {
                if ($this->check_token($_SESSION['id'], $_SESSION['token'])) {
                    return true;
                }
            }
        }

        return false;
    }

    function check_token($id, $token) {
        $numtoken = preg_replace("/[^0-9]/", "", $token);
        $numtoken = (int) $numtoken;
        if ($numtoken == $id) {

            return true;
        } else {

            return false;
        }
    }

    function is_authenticated() {
        if ($this->check_token($_SESSION['id'], $_SESSION['token']) && $_SESSION['authenticated']) {
            return true;
        } else {
            return false;
        }
    }

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

}

?>
