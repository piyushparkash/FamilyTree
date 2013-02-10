<?php

$db=something();
$db['something']="Piyush";
print_r($db);

function something()
{

$obj=array();
$obj['name']='Piyush';
return $obj;
}
