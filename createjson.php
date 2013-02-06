<?php

require "header.php";

global $db;

function getchild($id) {
    global $db;
    $finalarray = array();
    $query = $db->query("select * from member where sonof=$id and dontshow=0");
    while ($row = $db->fetch($query)) {
        $obj = array();
        $obj['id'] = $row["id"];
        $obj['name'] = $row['membername'];
        $obj['data'] = array(
            "dob" => ($row['dob'] ? strftime($row['dob'], "%d/%m/%Y") : ""),
            "relationship_status" => ($row['relationship_status'] == 0 ? "Single" :
                    "Married"),
            "relationship_status_id" => $row['relationship_status'],
            "alive" => ($row['alive'] == 0 ? "No" : "Yes"),
            "gender" => $row['gender'],
            "alive_id" => $row['alive'],
            'image' => empty($row['profilepic']) ? "common.png" : $row['profilepic']
        );
        $obj['children'] = getwife($row['id']);
        array_push($finalarray, $obj);
    }
    return $finalarray;
}

function getwife($id) {
    global $db;
    $finalarray=array();
    $row = $db->get("select * from member where id in (select related_to from member where id=$id)");
    $obj = array();
    // Space Tree Object if he has a wife
    if ($row) {
        $obj['id'] = $row['id'];
        $obj['name'] = $row['membername'];
        $obj['data'] = array(
            "dob" => ($row['dob'] ? strftime($row['dob'], "%d/%m/%Y") : ""),
            "relationship_status" => ($row['relationship_status'] == 0 ? "Single" :
                    "Married"),
            "relationship_status_id" => $row['relationship_status'],
            "alive" => ($row['alive'] == 0 ? "No" : "Yes"),
            "gender" => $row['gender'],
            "alive_id" => $row['alive'],
            'image' => empty($row['profilepic']) ? "common.png" : $row['profilepic']
        );
        $obj['children'] = getchild($id);
        array_push($finalarray, $obj);
    return $finalarray;
    }
    else
    {
        return NULL;
    }
}

$query = $db->query("select * from member where sonof is null and dontshow=0 and gender=0");
$row = $db->fetch($query);
$finalkey = array();
$finalkey['id'] = $row['id'];
$finalkey['name'] = $row['membername'];
$finalkey['data'] = array(
    "dob" => ($row['dob'] ? strftime($row['dob'], "%d/%m/%Y") : ""),
    "relationship_status" => ($row['relationship_status'] == 0 ? "Single" :
            "Married"),
    "relationship_status_id" => $row['relationship_status'],
    "alive" => ($row['alive'] == 0 ? "No" : "Yes"),
    "gender" => $row['gender'],
    "alive_id" => $row['alive'],
    'image' => empty($row['profilepic']) ? "common.png" : $row['profilepic']
);
$finalkey['children'] = getwife($row['id']);

echo json_encode($finalkey);
?>