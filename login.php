<?php

/**
 * @author Piyush
 * @copyright 2012
 */
require "header.php";
global $user, $vanshavali;

//Check for wp-login
if ($_GET['action'] == "wp_login" && $vanshavali->wp_login && $_GET['sub'] == 1) {
    if (!$user->wp_login_init($vanshavali->hostname . CALLBACK)) {
        //There is some error. Was not able to get the callback;
        trigger_error("There is some error. Was not able to get login through WP REST", E_USER_ERROR);
    }
} else if ($_GET['action'] == "wp_login" && $_GET['sub'] == 2 && $vanshavali->wp_login) {

    //Get the current user details
    $usr_details = $user->authenticate_wp($_GET['oauth_verifier']);

    //Check if we have to send data to some other page
    if ($_SESSION['sendtopage']) {
        $template->assign('data', $usr_details[0] + $usr_details[1]);
        $template->assign('sendtopage', $_SESSION['sendtopage']);
        $template->display('sendtopage.tpl');
        unset($_SESSION['sendtopage']);
        exit();
    }
    
    //Lets log the user in




    //@TODO: get the wordpress user details and match the local user
    // and then login the user

    exit();
} else if (isset($_POST['username'], $_POST['password'])) {
    $username = strtolower($_POST['username']);
    $pass = $_POST['password'];

    //Authenticate the user
    $ret = $user->login($username, $pass);

    //If user is still not authenticated then...There may be some error
    if (!$user->is_authenticated()) {
        $finalarray['error'] = 1;
        echo json_encode($finalarray);
        exit();
    } else {
        
    }

    $finalarray = array('membername' => $user->user['membername'], 'id' => $user->user['id']);
    echo json_encode($finalarray);
} else {

    //if nothing is sent the send them back to index.php
    header("Location:index.php");
}
?>