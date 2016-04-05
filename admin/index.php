<?php

//As this is admin section. It is allowed to show errors here.
ini_set('display_errors', 1);
error_reporting(E_ALL);


require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../header.php';

// first check if the user is allowed over here
global $user, $vanshavali, $template, $db;

if ($user->is_authenticated()) {
    //Check if user is acceptable
    $member = $vanshavali->getmember($user->get_user());

    if (!$member->isAdmin()) {
        header("Location:" . __DIR__);
    }
} else {
    //This user is not supposed to be here.
    header("Location:" . __DIR__);
}

//Now that user is authorized..Let him see the content

$template->display("header.tpl");

$template->display('admin/admin.main.tpl');

$template->display("footer.tpl");
