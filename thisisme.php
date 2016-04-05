<?php

require "header.php";
global $vanshavali, $db, $user;

//The original member present in tree
$ori_member = $vanshavali->getmember($_GET['id']);

$new_member = $vanshavali->getmember($user->user['id']);

//Copy the details of the new member to the original member
$details_changed = array('membername', 'username', 'password', 'dob', 'gender',
    'relationship_status', 'gaon', 'emailid', 'alive', 'aboutme', 'joined', 'approved', 'tokenforact', 'dontshow');

foreach ($column as $key => $value) {
    $ori_member->set($column, $new_member->get($column));
}

//Remove the new user, hence removing the duplicate
$new_member->remove();

$user->logout();

//Drop a mail to admin regarding this
$vanshavali->mailAdmin("mail.thisisme.tpl", array("newmember" => $new_member->data['membername'],
    "orimember" => $ori_member->data['membername']), "A user merged");

header("Location:index.php");

