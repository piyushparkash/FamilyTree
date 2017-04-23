<?php


require __DIR__ . "/../header.php";
require __DIR__ . "/../vanshavali/suggest.php";


//aunthenticate admin
$user->login("test", "test");

//We have all the classes initiated
echo "Trying to add family...";
$familyid = vanshavali::addfamily("Test Family");

if ($familyid)
{
    echo "We were able to add family";
}
else
{
    echo "addFamily Failed";
    exit(1);
}


echo "Trying to add member explicitly..\n";
$rootmemberID = vanshavali::addmember_explicit("Root Member", MALE,  $familyid);
if ($rootmemberID)
{
    echo "We were able to add member explicitly id $rootmemberID\n";
}
else
{
    echo "addmember_explicitly failed";
    exit(1);
}


$rootmember = vanshavali::getmember($rootmemberID);

//Set DOB, alive, relationship_status

$rootmember->edit("Root Edited Member", FEMALE, MARRIED, '', 0, true);

//TODO: Check if the edit suggest is there is database



