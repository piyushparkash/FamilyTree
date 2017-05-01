<?php

require __DIR__ . "/../header.php";
require __DIR__ . "/../vanshavali/suggest.php";

global $user;

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

echo "Trying to add wife to root member through suggest";

$suggest_id = $rootmember->addSpouse("Root Member Wife", true);

//Check if suggestion was added in database

$query = $db->query("select * from suggested_info where id = $suggest_id");

$result = $db->fetch($query);

$suggest_data = json_decode($result['new_value'], true);

if ($result['typesuggest'] == "addspouse" && $suggest_data['membername'] == "Root Member Wife" && $suggest_data['gender'] == FEMALE)
{
    echo "add spouse suggest working properly";
}

//Apply this suggest and check if it is working

$addSpouseSuggest = new suggest($suggest_id);

$addSpouseSuggest->apply();

$query = $db->query("select * from member where id = $rootmemberID");

$result = $db->fetch($query);

$query = $db->query("select * from member where id = " . $result['related_to']);

$result = $db->fetch($query);

if ($result['membername'] == "Root Member Wife" && $result['gender'] == FEMALE)
{
    echo "Successfully applied add spouse suggest";
}
else
{
    echo "Was not able to apply the suggest";
}

echo "Trying to add child through suggest";
//Check for addChild Suggest
$suggestid = $rootmember->addChild("Root Member First Child", MALE, true);


echo "Checking if addChild suggest is working properly...";

$query = $db->query("select * from suggested_info where id =  $suggestid");

$result = $db->fetch($query);

$suggest_data = json_decode($result['new_value'], true);

if ($result['typesuggest'] == ADDMEMBER && $suggest_data['membername'] == "Root Member First Child" && $suggest_data['gender'] == MALE)
{
    echo "AddChild suggest is working properly";
}
else
{
    echo "AddChild suggest is not working properly";
}

//Apply this suggest and check
$addChildsuggest = new suggest($suggestid);

$addChildsuggest->apply();

//check if this is reflecting

$query = $db->query("select * from member where sonof = $rootmemberID");
$result = $db->fetch($query);

if ($result['membername'] == "Root Member First Child" && $result['gender'] == MALE)
{
    echo "AddChild Suggest successfully applied";
}
else
{
    echo "AddChild Suggest is not applied is apply for addChild is not working";
}


//Add Parents suggest check
echo "Trying to add parents through suggest";
//Check for addparents Suggest
$suggestid = $rootmember->addParents("Root Member Father", "Root Member Mother", true);


echo "Checking if addparents suggest is working properly...";

$query = $db->query("select * from suggested_info where id =  $suggestid");

$result = $db->fetch($query);

$suggest_data = json_decode($result['new_value'], true);

if ($result['typesuggest'] == ADDPARENTS && $suggest_data['fathername'] == "Root Member Father" && $suggest_data['mothername'] == "Root Member Mother")
{
    echo "Addparents suggest is working properly";
}
else
{
    echo "Addparents suggest is not working properly";
}

$addParentsSuggest = new suggest($suggestid);

$addParentsSuggest->apply();

//Check if add parents suggest was applied, any parents of root member is there
$query = $db->query("select * from member where id  in (select sonof from member where id = $rootmemberID)");
$result = $db->fetch($query);

if ($result['membername'] == "Root Member Father" && $result['gender'] == MALE && !empty($result['related_to']))
{
    echo "Add Parents suggest successfully applied";
}
else
{
    echo "Add Parents suggest successfully not applied";
}




vanshavali::delFamily($familyid);