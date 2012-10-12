<?php

/**
 * @author Piyush
 * @copyright 2011
 */
error_reporting(E_ALL & ~E_NOTICE);

//If config file exists then include it else leave it
if (file_exists("config.php")) {
    if (is_readable("config.php")) {
        require_once("config.php");
    }
}

global $db, $template, $user, $vanshavali;

//Initialize Global variables
require_once 'template/template.php';
require_once 'db/db.php';
require_once 'user/user.php';
require_once 'vanshavali/vanshavali.php';
$template = new template();
$db = new db();
$vanshavali=new vanshavali();

//Select the defualt database
if (isset($config['database']) and !empty($config['database'])) {
    $db->select_db($config['database']);
}

$user=new user();

//Initialize custom error handler
function vanshavali_error($level, $message, $file, $line, $context) {
    global $template;
    switch ($level) {
        case E_USER_ERROR:
        case E_USER_WARNING:
            $template->assign("message", $message);
            $template->assign("lineno", $line);
            $template->assign("file", $file);
            $template->assign("context", $context);
            $template->header();
            $template->display("error_high.tpl");
            exit(); //exit the file as error level is high
            break;
        case E_USER_NOTICE:
            $template->assign("message", $message);
            $template->assign("lineno", $line);
            $template->assign("file", $file);
            $template->assign("context", $context);
            $template->display("error_low.tpl");
    }
}

set_error_handler("vanshavali_error");  //Set the custom error handler


function hassons($memberid) {
    $query = executequery("select * from member where sonof=" . $memberid);
    if (mysql_fetch_row($query) == false) {
        return false;
    } else {
        return true;
    }
}

function deletesons($memberid) {
    $query = executequery("delete from member where sonof=" . $memberid);
    return $query;
}

function fatherid($memberid) {
    $query = executequery("select* from member where id=" . $memberid);
    return mysql_result($query, 0, "sonof");
}

function navigationstring($memberid) {
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

function fileext($filename, $ext = true) {
    $filename = basename($filename);
    $arr = explode(".", $filename);
    $count = count($arr);
    if ($ext) {
        return $arr[$count - 1];
    } else {
        return implode("", array_slice($arr, 0, $count - 1));
    }
}
