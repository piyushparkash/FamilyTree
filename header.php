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
$vanshavali = new vanshavali();

//Select the defualt database
if (isset($config['database']) and !empty($config['database'])) {
    $db->select_db($config['database']);
}

$user = new user();

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
            //If request is AJAX then
            if ($_SERVER['HTTP_X_REQUESTED_WITH']) {
                //Prepare the array
                $errorarray = array("success" => 0, "message" => $message);
                echo json_encode($errorarray);
            } else {
                $template->assign("message", $message);
                $template->assign("lineno", $line);
                $template->assign("file", $file);
                $template->assign("context", $context);
                $template->display("error_low.tpl");
            }
    }
}

set_error_handler("vanshavali_error");  //Set the custom error handler

/***********************************************
 * After this Function sections start. All the global function used 
 * within the project has been declared below this
 ***********************************************/

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

function ajaxSuccess($data=NULL)
{
    echo json_encode(array("success" => 1, "data" => $data));
}

function ajaxError($data=NULL)
{
    echo json_encode(array("success" => 0, "data" => $data));
}

function createstruct($row) {
    $obj = array();
    $obj['id'] = $row["id"];
    $obj['name'] = $row['membername'];
    $obj['data'] = array(
        "dob" => ($row['dob'] ? strftime($row['dob'], "%d/%m/%Y") : ""),
        "relationship_status" => ($row['relationship_status'] == 0 ? "Single" :
                "Married"),
        "relationship_status_id" => $row['relationship_status'],
        "alive" => ($row['alive'] == 0 ? "Deceased" : "Living"),
        "gender" => $row['gender'],
        "alive_id" => $row['alive'],
        'image' => empty($row['profilepic']) ? "common.png" : $row['profilepic'],
        'familyid' => $row['family_id']
    );
    return $obj;
}

function getchild($id) {
    global $db;
    $finalarray = array();
    $query = $db->query("select * from member where sonof=$id and dontshow=0");
    while ($row = $db->fetch($query)) {
        $obj = createstruct($row);
        $obj['children'] = getwife($row['id']);
        array_push($finalarray, $obj);
    }
    return $finalarray;
}

function getwife($id) {
    global $db;
    $finalarray = array();
    $row = $db->get("select * from member where id in (select related_to from member where id=$id)");
    $obj = array();
    // Space Tree Object if he has a wife
    if ($row) {
        $obj=  createstruct($row);
        $obj['children'] = getchild($id);
        array_push($finalarray, $obj);
        return $finalarray;
    } else {
        return NULL;
    }
}