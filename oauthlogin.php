<?php

require 'header.php';
error_reporting(E_ALL);
ini_set("display_errors", "On");


if (!isset($_GET['code'])) {
    $user->wp_login_init();
} else {
    //get the code, access token and login url and redirect
    $userDetails = $user->authenticate_wp($_GET['code']);
//    echo json_encode($userDetails);   
    //Login in the user in Family Tree
    $user->authenticate_thr_wp($userDetails[1]['result']['id']);
    
    
    //Check if to redirect
    $redirect = $_SESSION['redirect_to'];
    if ($redirect)
    {
        unset($_SESSION['redirect_to']);
        header("Location: $redirect");
    }
    else
    {
        //Redirect to homepage
        header("Location: index.php");
    }
    
}