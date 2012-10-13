<?php

require "header.php";

global $db;

function getchild($id) {
    global $db;
    $finalarray = array();
    $query = $db->query("select * from member where sonof=$id");
    while ($row = $db->fetch($query)) {
        $obj = array();
        $obj['id'] = $row["id"];
        $obj['name'] = $row['membername'];
        $obj['data'] = array(
            "dob" => ($row['dob'] ? $row['dob'] : "unknown"),
            "relationship_status" => ($row['relationship_status'] == 0 ? "Single" :
                    "Married"),
            "alive" => ($row['alive'] == 0 ? "No" : "Yes"),
            'image' => $row['profilepic']);
        $obj['children'] = getchild($row['id']);
        array_push($finalarray, $obj);
    }
    return $finalarray;
}

$query = $db->query("select * from member where sonof=-1");
$row = $db->fetch($query);
$finalkey = array();
$finalkey['id'] = $row['id'];
$finalkey['name'] = $row['membername'];
$finalkey['data'] = array(
    "dob" => ($row['dob'] ? $row['dob'] : "unknown"),
    "relationship_status" => ($row['relationship_status'] == 0 ? "Single" :
            "Married"),
    "alive" => ($row['alive'] == 0 ? "No" : "Yes"));
$finalkey['children'] = getchild($row['id']);

echo json_encode($finalkey);
?>