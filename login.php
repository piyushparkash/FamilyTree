<?php

/**
 * @author Piyush
 * @copyright 2012
 */
require "header.php";
global $user;

if (isset($_POST['username'], $_POST['password'])) {
    $username = strtolower($_POST['username']);
    $pass = $_POST['password'];
    
    //Authenticate the user
    $ret = $user->login($username, $pass);
    
    
    if ($ret['error'] == 2) { //If user hasn't activated his account
        echo json_encode(array("error" => 2)); //The error code is 2
        exit();
    }
    
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