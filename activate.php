<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require("header.php");
connecttodatabase();
$emailid = $_GET['emailid'];
$token = $_GET['token'];
$query = executequery("Select * from member where tokenforact='$token' and emailid='$emailid'");
$row = mysql_fetch_array($query);
if ($emailid == $row['emailid'] and $token == $row['tokenforact']) {
	executequery("update member set approved=1 where id=".$row['id']);
	authenticateuser($row['id'],$row['membername']);
    header("Location:index.php?#".$row['id']);
} else {
    echo "<h1>An Unexpected error occured</h1>";
}