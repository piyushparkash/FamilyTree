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

//Lets add a sample Wife, Child and Parents and then try to delete each of them

$addspousesuggest = $rootmember->addSpouse("Root Member Wife");
$addchildsuggest = $rootmember->addChild("Root Member First Child", MALE);
$addparentssuggest = $rootmember->addParents("Root Member Father", "Root Member Mother");

//Apply all the suggestions and then delete the members

$suggest = new suggest($addspousesuggest);
$suggest->apply();

$suggest = new suggest($addchildsuggest);
$suggest->apply();

$suggest = new suggest($addparentssuggest);
$suggest->apply();

$rootmember->populate_data($rootmember->id);

//Get the child of root member
$rootmemberschild = $rootmember->get_sons();

//Remove the child of root member
$removechildsuggest = $rootmemberschild[0]->remove(true);


//Check in the db if remove request has gone or not
$query = $db->query("select * from suggested_info where id = $removechildsuggest");
$result = $db->fetch($query);

if ($result['typesuggest'] == "delmember")
{
    echo "Remove Suggest is working properly. Now Lets apply this suggest";
}
else
{
    echo "Remove suggest is not working";
}

$suggest = new suggest($removechildsuggest);
$suggest->apply();

//Check if suggest was applied
$query = $db->query("select id, dontshow from member where sonof in (select id from member where id = $rootmemberID)");
$result = $db->fetch($query);

if ($result['dontshow'] == 1)
{
    echo "Remove Suggest successfully applied";
}
else
{
    echo "Remove Suggest was not applied";
}

$rootmember->populate_data($rootmemberID);

//First Try and remove parents
$removeparentsuggest = $rootmember->removeParents(true);

//Check in the db if remove parent request has gone or not
$query = $db->query("select * from suggested_info where id = $removeparentsuggest");
$result = $db->fetch($query);

if ($result['typesuggest'] == "removeparents")
{
    echo "Remove parents suggest is working just fine";
}
else
{
    echo "Remove suggest is not working fine.";
}

$suggest = new suggest($removeparentsuggest);
$suggest->apply();

//Check if suggest was applied and parents have been removed from db
$query = $db->query("select f.dontshow as fatherdontshow, m.dontshow as motherdontshow from member m, member f where f.id in (select sonof from member where id = $rootmemberID) and m.id = f.related_to");
$result = $db->fetch($query);

if ($result['fatherdontshow'] ==1 and $result['motherdontshow'] ==1)
{
    echo "Remove parents suggest successfully applied";
}
else
{
    echo "Remove parents suggest not applied";
}


//Remove Wife
$removespousesuggest = $rootmember->removeSpouse(true);

$query = $db->query("select * from suggested_info where id = $removespousesuggest");
$result = $db->fetch($query);

if ($result['typesuggest'] == "removespouse")
{
    echo "remove spouse suggest is working properly";
}
else
{
    echo "Rmeove Spouse suggest is not working properly";
}

$suggest = new suggest($removespousesuggest);
$suggest->apply();

//Check if remove spouse suggest was properly applied

$query = $db->query("select dontshow from member where id in (select related_to from member where id = $rootmemberID)");
$result = $db->fetch($query);

if ($result['dontshow'] == 1)
{
    echo "Remove Spouse successfully applied";
}
else
{
    echo "Remove Spouse was not applied";
}

vanshavali::delFamily($familyid);