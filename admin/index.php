<?php

//As this is admin section. It is allowed to show errors here.
ini_set('display_errors', 1);
error_reporting(E_ALL);


require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../header.php';
require_once __DIR__ . '/modules.php';

// first check if the user is allowed over here
global $user, $vanshavali, $template, $db;

//Initialize modules
$module = new module();

$ft_root_path = "./../";

if ($user->is_authenticated()) {
    //Check if user is acceptable
    $member = $vanshavali->getmember($_SESSION['id']);

    if (!$member->isAdmin()) {
        header("Location:" . $ft_root_path);
    }
} else {
    //This user is not supposed to be here.
    header("Location:" . $ft_root_path);
}

//Now that user is authorized..Let him see the content


$template->display("header.tpl");

$module
//Display content according to mode and submode

$template->display('admin/admin.main.tpl');

$template->display("footer.tpl");
