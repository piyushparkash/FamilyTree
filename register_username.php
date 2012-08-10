<?php

/**
 * @author lolkittens
 * @copyright 2012
 */
require ("header.php");
connecttodatabase();
$partial = $_GET['pt'];
switch ($_GET['action']) {
    case "register":
        $query = executequery("select * from member where membername like '%$partial%' and username='' and password=''");
        $usernames = array();
        while ($row = mysql_fetch_array($query)) {
            $usernames[$row["id"]] = $row['membername'];

        }
        //for ($temp=0; $temp<count($usernames))
        echo json_encode($usernames);
        break;
    case "search":
        $query = executequery("select * from member where membername like '%$partial%'");
        $usernames = array();
        while ($row = mysql_fetch_array($query)) {
            $usernames[$row["id"]] = $row['membername'];

        }
        //for ($temp=0; $temp<count($usernames))
        echo json_encode($usernames);
        break;
    default:
    break;
}



?>