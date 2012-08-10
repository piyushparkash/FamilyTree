<?php

/**
 * @author lolkittens
 * @copyright 2012
 * getdata.php used to get certain data from database
 */

//connect to database
require "header.php";
connecttodatabase();

//get the type of data to be extracted
switch ($_POST['action']) {
	
	//when to check whether a given username already exists or not
	case "username_check":
		$username=$_POST['username'];
		$query=executequery("select count(*) as no from member where username='$username'");
		$row=mysql_fetch_array($query);
		
		//if count is >1 then there are user with that username
    	if ($row['no']>1)
    	{
    		echo json_encode(array("yes" => 1));
    	}
    	else
    	{
    		echo json_encode(array("yes" => 0));
    	}
    default: //when nothing matches
        break;
}

?>