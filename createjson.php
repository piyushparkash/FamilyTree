<?php

require "header.php";

global $db;
$familyid = $_GET['familyid'];

//Family Id
if (!$familyid) {
    $familyid = 1;
}



$query = $db->query("select * from member where sonof is null and dontshow=0 and gender=0 and family_id=$familyid");
$row = $db->fetch($query);
$finalkey = array();
$finalkey['id'] = $row['id'];
$finalkey['name'] = $row['membername'];
$finalkey['data'] = array(
    "dob" => ($row['dob'] ? strftime($row['dob'], "%d/%m/%Y") : ""),
    "relationship_status" => ($row['relationship_status'] == 0 ? "Single" :
            "Married"),
    "relationship_status_id" => $row['relationship_status'],
    "alive" => ($row['alive'] == 0 ? "Deceased" : "Living"),
    "gender" => $row['gender'],
    "alive_id" => $row['alive'],
    'image' => empty($row['profilepic']) ? "common.png" : $row['profilepic']
);
$finalkey['children'] = getwife($row['id']);

echo json_encode($finalkey);
?>