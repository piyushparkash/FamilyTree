<?php

require 'header.php';
global $template, $db, $vanshavali;

if ($_POST['forgotpasswordform']) {
    //Validations are done. Just do the job here.
    $newPassword = $_POST['new_password'];
    $oldPassword = $_POST['old_password'];
    $member = $db->get("select * from member where tokenforact=" . $_POST['tokenforact']);

    //Get the member class
    $member = $vanshavali->getmember($member['id']);

    //Check if the old password provided is correct
    if (md5($oldPassword) != $member->get("password")) {
        //Assign Error in the form
        $template->assign("oldpassworderror", 1);
    } else {
        //Everything went fine, so we are here. Lets change the password
        $member->changePassword($newPassword);
        
        //Everything went fine here, so we don't need to show the 
        //Forgot password again
        header("Location:index.php?passwordchanged=1");
    }
}


//Fetch the tokenforact which is send to be sent in the mail of the user
if (empty($_GET['tokenforact'])) {
    header("Location:index.php");
}

//Check if there is a member with that tokenforact
$query = $db->get("select * from member where tokenforact = '" . $_GET['tokenforact']. "'");

if (!$query) {
    header("Location:index.php");
} else {

    $template->assign("tokenforact", $_GET['tokenforact']);
    $template->display("forgotpassword.form.tpl");
}

