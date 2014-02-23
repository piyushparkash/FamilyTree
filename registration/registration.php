<?php

/**
 * All the Registration Process will be here
 *
 * @author piyush
 */
class registration {

    public function __construct($mode, $sub) {
        switch ($mode) {
            case "userdetails":
            default :
                $this->userdetails($mode, $sub);
                break;
            case "accountdetails":
                $this->accountdetails($mode, $sub);
                break;
        }
    }

    public function userdetails($mode, $sub) {
        global $template;
        $sub = ($sub == null) ? 1 : $sub;
        if ($sub == 1) {
            $template->header();
            $template->display("userdetails.tpl");
        } else {

            // @TODO: Skipped here the validations. Also make changes in the html
            //Passing the data
            $template->assign('pastdata', $_POST);
            $template->header();
            $template->display("accountdetails.tpl");
        }
    }

    public function accountdetails($mode, $sub) {
        global $template, $vanshavali;
        $sub = $sub == null ? 2 : $sub;

        if ($sub == 2) {
            //Parse the data and register the user
            $name = $_POST['name'];
            $username = $_POST['username'];
            $password = $_POST['password'];
            $email = $_POST['email'];
            $dob = $_POST['dob'];
            $gender = $_POST['gender'];
            $village = $_POST['village'];
            $aboutme = $_POST['aboutme'];

            //Parse the dob and convert to timestamp
            preg_match("/([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4,4})/", $_POST['register_dob'], $matches);
            $dob = mktime(0, 0, 0, $matches[2], $matches[1], $matches[3]);
            
            //Register the user
            $vanshavali->register(array($username, $password, $dob, $gender, SINGLE, $village, $email, $aboutme, null, $name));
            
            //Welcome the user
            header("Location: welcome.php");
        }
    }

}
