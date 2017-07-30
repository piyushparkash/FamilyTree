<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once 'header.php';

global $db;

$from = $_POST['from'];
$to = $_POST['to'];

if (empty($from) or empty($to))
{
    echo "Login to view relation";
}
$result = vanshavali::calculateRelation($from, $to);

if (is_array($result))
{
    echo json_encode(array("relation" => $result[0], "error" => 0));
}
else
{
    echo json_decode(array("relation" => "unknown", "error" => 1));
}

//echo $vanshavali->distanceFromTop($vanshavali->getmember(178));