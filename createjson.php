<?php

require "header.php";

global $db;
$familyid = $_GET['familyid'];

//Get the family id of the family to be shown
if (!$familyid) {
    $familyid = 1;
}


//Get the member who is son of no one and is male and is of given family id
$query = $db->query("select * from member where sonof is null and dontshow=0 and gender=0 and family_id=$familyid");
$row = $db->fetch($query);

//Create a infovis struct
$finalkey = createstruct($row);
$finalkey['children'] = getwife($row['id']);

echo json_encode($finalkey);
?>