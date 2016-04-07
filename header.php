<?php

/**
 * @author Piyush
 * @copyright 2011
 */
error_reporting(E_ALL);

require_once __DIR__ . '/constants.php';

//If config file exists then include it else leave it
if (file_exists(__DIR__ . "/config.php")) {
    if (is_readable(__DIR__ . "/config.php")) {
        require_once(__DIR__ . "/config.php");
    }
}

global $db, $template, $user, $vanshavali;

//Initialize Global variables
require_once __DIR__  . '/template/template.php';
require_once __DIR__  . '/db/db.php';
require_once __DIR__  . '/user/user.php';
require_once __DIR__  . '/vanshavali/vanshavali.php';
require_once __DIR__  . '/functions.php';
require_once __DIR__  . '/suggest/suggest_handler.php';
$template = new template();
$db = new db();
$vanshavali = new vanshavali();

//Assign the Email Address of admin in the App
$vanshavali->admin_email = $config['admin_email'];

//Select the default database
if (isset($config['database']) and !empty($config['database'])) {
    $db->select_db($config['database']);
}

$user = new user();
$suggest_handler = new suggest_handler();

//Register the basic suggests

$suggest_handler->register_handler(ADDMEMBER, "suggest.add.tpl", array("suggested_by", "suggested_to", "newvalue", "sod", "oldvalue"), ADD);
$suggest_handler->register_handler(NAME, "suggest.edit.name.tpl", array("suggested_by", "suggested_to", "oldvalue", "newvalue", "sod"), MODIFY);
$suggest_handler->register_handler(DOB, "suggest.edit.dob.tpl", array("suggested_by", "suggested_to", "oldvalue", "newvalue", "sod"), MODIFY);
$suggest_handler->register_handler(GAON, "suggest.edit.gaon.tpl", array("suggested_by", "suggested_to", "oldvalue", "newvalue", "sod"), MODIFY);
$suggest_handler->register_handler(RELATIONSHIP, "suggest.edit.relationship.tpl", array("suggested_by", "suggested_to", "oldvalue", "newvalue", "sod"), MODIFY);
$suggest_handler->register_handler(ALIVE, "suggest.edit.alive.tpl", array("suggested_by", "suggested_to", "oldvalue", "newvalue", "sod"), MODIFY);
$suggest_handler->register_handler(GENDER, "suggest.edit.gender.tpl", array("suggested_by", "suggested_to", "oldvalue", "newvalue", "sod"), MODIFY);
$suggest_handler->register_handler(DELMEMBER, "suggest.del.tpl", array("suggested_by", "suggested_to", "newvalue", "oldvalue", "sod"), MODIFY);
$suggest_handler->register_handler(ADDSPOUSE, "suggest.add.spouse.tpl", array("suggested_by", "suggested_to", "newvalue", "oldvalue", "sod"), ADD);

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
            if (@$_SERVER['HTTP_X_REQUESTED_WITH']) {
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
?>
