<?php

require "header.php";

global $db, $vanshavali, $user;

if ($user->is_authenticated()) {
    $familyid = $user->user['family_id'];
} else {
    $familyid = null;
}

$head = $vanshavali->getHeadofFamily($familyid);
$head = new member($head);

//Create a infovis struct
$finalkey = $vanshavali->createstruct($head->data);
$finalkey['children'] = $vanshavali->getwife($head->data['id']);

echo json_encode($finalkey);
?>