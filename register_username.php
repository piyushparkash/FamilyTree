<?php

/**
 * @author Piyush
 * @copyright 2012
 */
require ("header.php");
global $db;

$partial = $_GET['pt'];
switch ($_GET['action']) {
    case "register":
        $query = $db->query("select * from member where membername like '%$partial%' and username='' and password=''");
        $usernames = array();
        while ($row = $db->fetch($query)) {
            $usernames[$row["id"]] = $row['membername'];

        }
        //for ($temp=0; $temp<count($usernames))
        echo json_encode($usernames);
        break;
    case "search":
        $query = $db->query("select * from member where membername like '%$partial%' and membername not IN ('Mother', 'Father', 'Wife')");
        $usernames = array();
        while ($row = $db->fetch($query)) {
            $usernames[$row["id"]] = array("name" => $row['membername'],"familyid" => $row['family_id']);

        }
        //for ($temp=0; $temp<count($usernames))
        echo json_encode($usernames);
        break;
    default:
    break;
}



?>