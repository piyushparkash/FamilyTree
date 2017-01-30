<?php

require 'header.php';
global $template, $vanshavali, $user, $db;

//If there is submitted data
if (isset($_POST['register_submit'])) {
//Collect all the variables
    $id = $_POST['register_id'];
    if (empty($id)) {
        $id = null;  //Letting in member who is not part of family
    }
    $membername = $_POST['register_name'];

    $username = $_POST['register_username'];

    $password = $_POST['register_password'];

//Convert date to Unix TimeStamp
    preg_match("/([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4,4})/", $_POST['register_dob'], $matches);
    $dob = mktime(0, 0, 0, $matches[2], $matches[1], $matches[3]);

    $gender = $_POST['register_gender'];
    $relation = $_POST['register_relationship'];
    $gaon = $_POST['register_gaon'];
    $email = $_POST['register_email'];
    $about = $_POST['register_about'];
    $familyid = $_POST['familyid'];

    if ($vanshavali->wp_login) {
        $wp_id = $_SESSION['wpid']; //Current wordpress user
    }
    
    if ($vanshavali->register(array(
                $username, $password, $dob, $gender, $relation, $gaon, $email, $about, $id, $membername, $familyid,
                $wp_id
            ))) {
        if ($_POST['is_admin']) {

            //Make the just added user admin
            $vanshavali->makeAdmin($db->last_id()) or trigger_error("Member registered but cannot make member admin. Fatal Error!", E_USER_ERROR);
        }

        //Drop a mail to admin regarding this
        $vanshavali->mailAdmin('mail.admin.newuserregister.tpl', array("membername" => $membername), "New User Joined");

        header("Location:welcome.php");
    }
}

//Redirect if user is already logged in
if ($user->is_authenticated())
{
    header("Location:index.php");
}

//If WP Login then wpid should be set
if (!$_SESSION['wpid'])
{
    $_SESSION['redirect_to'] = "register.php";
    header("Location:index.php");
}


$template->header();
$template->assign("is_wordpress_enabled", $vanshavali->wp_login);
$template->display('user.main.tpl');
$template->display('register.form.tpl');
$template->footer();
