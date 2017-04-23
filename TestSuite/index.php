<?php

//Tests for all the API and database validation

//Test for addChild db and suggest

require __DIR__ . "/../header.php";

global $user;

//aunthenticate admin
$user->login("piyush", "piyush");

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

//Check for add son

echo "Trying to add member directly";

//add member directly
$rootmember = vanshavali::getmember($rootmemberID);

//Try to add wife of the member first
$wifeid = $rootmember->addSpouse("Root Member Wife");

$rootmember->populate_data($rootmember->id);

if (!$rootmember->data['related_to'])
{
    //Related to is not populated and is not working
    echo "addSpouse is not working. addSpouse Failed";
    exit(1);
}

//Normally check the database where there is data in it
$query = $db->query("select * from member where id = " . $rootmember->data['related_to']);
$result = $db->fetch($query);

//Check the related to of the wife and related to off the root member
if ($result['related_to'] == $rootmember->id)
{
    //We are done here
    echo "Wife had been added and addSpouse is working properly";
}

//Now try adding the first child
$rootmember->addChild("First Son", MALE);

echo "select * from member where sonof=$rootmemberID";

//Manaully check if the member is there in db
$query = $db->query("select * from member where sonof=$rootmemberID");
$result = $db->fetch($query);

if ($result['membername'] == "First Son" && $result['gender'] == MALE)
{
    echo "addChild working properly";
}
else
{
    echo "addChild Failed";
}

//Check whether addparents is working or not
$rootmember->addParents("Root Member Father", "Root Member Mother");

$rootmember->populate_data($rootmember->id);

if ($rootmember->data['sonof'])
{
    echo "addParents working properly";
}
else
{
    echo "addParents not working";
}

vanshavali::delFamily($familyid);

//Remove the members that were added
if ($db->query("delete from member where id in ($rootmemberID, $result[0], $wifeid)"))
{
    echo "We were able to delete the temp members created";
}