<?php

/**
 * @author Piyush
 * @copyright 2011
 */
//Global Variables
// this will contain all the information of the current loged in user.
$host=null; //set these variables according to your configuration
$username=null;
$password=null;
session_start();
$_SESSION['profilepic'] = "common.jpg";
$_SESSION['authenticated'] = is_authenticated(); // will set to true if the user has loged in..
$_SESSION['signeduser'] = array();
//$query = executequery("select * from member where id=".$_COOKIE['id']);
//$_SESSION["signeduser"] = mysql_fetch_array($query);

function connecttodatabase()
{
    global $data;
    $data = mysql_connect($host, $username, $password);
    if ($data == false) {
        echo "Error Connecting to the database";
        exit();
    }
    mysql_select_db("bansavali");
}

function is_authenticated()
{
    global $_SESSION;

    if (!isset($_COOKIE['token'], $_COOKIE['membername'], $_COOKIE['id'])) {

        return false;
    }
    //$query = executequery("select * from member where id=".$_COOKIE['id']);
    //$_SESSION["signeduser"] = mysql_fetch_array($query);
    $token = $_COOKIE['token'];
    $numtoken = preg_replace("/[^0-9]/", "", $token);
    $numtoken = (int)$numtoken;
    if ($numtoken == $_COOKIE['id']) {

        return true;
    } else {

        return false;
    }
}
function vanshavali_mail($to, $subject, $body)
{
    $user_email = "me@vanshavali.co.cc"; // valid POST email address

    $headers = "From: $user_email\r\n";
    $headers .= "Return-Path: $to\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
    $headers .= 'MIME-Version: 1.0' . "\n";
    $headers .= 'Content-type: text/html; UTF-8' . "\r\n";

    if (!mail($to, $subject, $body, $headers))
    {
    	die("Some error occured!");
    }
}
function authenticateuser($id, $membername)
{
    global $_SESSION;
    $token = generate_token($id);
    setcookie("membername", $membername, 0, "/");
    setcookie("id", $id, 0, "/");
    setcookie("token", $token, 0, "/");
    $_SESSION['authenticated'] = true;

}

function generate_token($id = "")
{
    $codelenght = 20;
    for ($newcode = "", $newcode_length = 0; $newcode_length < $codelenght; $newcode_length++) {
        if ($newcode_length == 5) {
            $newcode = $newcode . $id;
        }
        $x = 1;
        $y = 2;
        $part = rand($x, $y);
        if ($part == 1) {
            $a = 65;
            $b = 90;
        } // UpperCase
        if ($part == 2) {
            $a = 97;
            $b = 122;
        } // LowerCase
        $newcode = $newcode . chr(rand($a, $b));
    }
    return $newcode;
}

function executequery($sql)
{
    $data = mysql_query($sql);
    if ($data == false) {
        echo mysql_error();
        return false;
    } else {
        return $data;
    }
}

function hassons($memberid)
{
    $query = executequery("select * from member where sonof=" . $memberid);
    if (mysql_fetch_row($query) == false) {
        return false;
    } else {
        return true;
    }
}

function deletesons($memberid)
{
    $query = executequery("delete from member where sonof=" . $memberid);
    return $query;
}

function fatherid($memberid)
{
    $query = executequery("select* from member where id=" . $memberid);
    return mysql_result($query, 0, "sonof");
}

function navigationstring($memberid)
{
    $current = $memberid;
    $temp = $current;
    $father = "";
    $resultantstring = "";
    while (1) {
        $father = mysql_fetch_array(executequery("select * from member where ID=" . $temp));
        $resultantstring = "<a href='index.php?showmember=" . $father['ID'] . "'>" . $father['membername'] .
            "</a>>" . $resultantstring;
        $temp = $father['sonof'];
        if ($temp == null) {
            break;
        }
    }
    echo $resultantstring;
}

function fileext($filename, $ext = true)
{
    $filename = basename($filename);
    $arr = explode(".", $filename);
    $count = count($arr);
    if ($ext) {
        return $arr[$count - 1];
    } else {
        return implode("", array_slice($arr, 0, $count - 1));
    }
}
