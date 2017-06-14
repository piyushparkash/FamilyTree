<?php

require "header.php";

global $db, $user;

error_reporting(E_ALL);
ini_set("display_errors", "On");
$familyid = $_GET['familyid'];

echo vanshavali::genTreeJSON($familyid);