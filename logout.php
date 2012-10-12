<?php

/*
 * @author Piyush
 * 
 */

require "header.php";
global $user, $db;


$user->logout();
header("Location:index.php");
?>