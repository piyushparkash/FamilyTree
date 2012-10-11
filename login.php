<?php

/**
 * @author Piyush
 * @copyright 2012
 */
require "header.php";
global $user;

if (isset($_POST['username'], $_POST['password'])) {
    $username = $_POST['username'];
    $pass = $_POST['password'];
    
    $ret = $user->login($username, $pass);
    if ($ret['error'] == 2) { //If user hasn't activated his account
        echo json_encode(array("error" => 2)); //The error code is 2
        exit();
    }
        if (!$user->is_authenticated()) {
            $finalarray['error'] = 1;
            echo json_encode($finalarray);
            exit();
        } else {
            
        }

        $finalarray = array('membername' => $user->user['membername'], 'id' => $user->user['id']);
        echo json_encode($finalarray);
    
} else {
    header("Location:index.php");
}
?>