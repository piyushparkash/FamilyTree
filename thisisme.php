<?php

require "header.php";
global $vanshavali, $db, $user;

$member=$vanshavali->getmember($_GET['id']);

$sql="update member set sonof=".$member->data['sonof']." where id=".$user->user['id'];

if ($db->query($sql) && $db->query("delete from member where id=".$_GET['id']))
{
    $user->logout();
    header("Location:index.php");
}
?>
