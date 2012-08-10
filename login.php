<?php

/**
 * @author lolkittens
 * @copyright 2012
 */
require "header.php";
if (isset($_POST['username'],$_POST['password']))
{
$username = $_POST['username'];
$pass = $_POST['password'];
$finalarray = array();
connecttodatabase();
$query = executequery("select * from member where username='$username' and password='$pass'");
$row = mysql_fetch_array($query);
if (!$row) {
    $finalarray['error'] = 1;
    echo json_encode($finalarray);
    exit();
} else {
	if ($row['approved']==0) //If user hasn't activated his account
	{
		echo json_encode(array("error" => 2)); //The error code is 2
		exit();
	}
	//Else
    authenticateuser($row['id'], $row['membername']);
    $finalarray = array('membername' => $row['membername'], 'id' => $row['id']);
    echo json_encode($finalarray);
}
}
else
{
    header("Location:index.html");
}
?>