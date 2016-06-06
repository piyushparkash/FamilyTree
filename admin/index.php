<?php

//As this is admin section. It is allowed to show errors here.
//ini_set('display_errors', 1);
//error_reporting(E_ALL);
define(basepath, "./../");

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../header.php';
require_once __DIR__ . '/module.php';

// first check if the user is allowed over here
global $user, $vanshavali, $template, $db;

//Initialize modules



if ($user->is_authenticated()) {
    //Check if user is acceptable
    $member = $vanshavali->getmember($_SESSION['id']);

    if (!$member->isAdmin()) {
        header("Location:" . basepath);
    }
} else {
    //This user is not supposed to be here.
    header("Location:" . basepath);
}

//Now that user is authorized..Let him see the content


$template->header(basepath);

//Display content according to mode and submode
if (empty($_GET['mode'])) {
    //Lets switch to default module to be loaded
    header("Location: index.php?mode=adminuser");
}


$template->display('admin/admin.main.header.tpl');

$module = new module($_GET, $template, $db);

$template->display('admin/admin.main.footer.tpl');


$template->footer();
