<?php

require "header.php";
connecttodatabase();

function getchild($id)
{
    $finalarray = array();
    $query = executequery("select * from member where sonof=$id");
    while ($row = mysql_fetch_array($query)) {
        $obj = array();
        $obj['id'] = $row["id"];
        $obj['name'] = $row['membername'];
        $obj['data'] = array(
            "dob" => ($row['dob'] ? $row['dob'] : "unknown"),
            "relationship_status" => ($row['relationship_status'] == 0 ? "Single" :
                "Married"),
            "alive" => ($row['alive'] == 0 ? "No" : "Yes"));
        $obj['children'] = getchild($row['id']);
        array_push($finalarray, $obj);
    }
    return $finalarray;
}

$query = executequery("select * from member where sonof=-1");
$row = mysql_fetch_array($query);
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