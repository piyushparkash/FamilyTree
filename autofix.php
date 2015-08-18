<?php

require 'header.php';
global $db, $vanshavali;

$query = $db->query("select * from member");

while ($row = $db->fetch($query)) {
    $member = $vanshavali->getmember($row['id']); //This will autofix all the abnormalities of the member
}

header("Location:index.php");