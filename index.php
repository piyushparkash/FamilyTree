<?php

require 'header.php';
//If config.php doesn't exist, probably not installed
if (!file_exists("config.php")) {
    require_once("install/install.php");
    $install = new install();
    exit();
}

//Now that the things are installed
global $template, $user;

//Check if config.php is readable if not then tell user to set the permissions manually
if (!is_readable("config.php")) {
    $template->header();
    $template->display("config.error.tpl");
    exit();
}



//Display the header and basic contents
$template->header();
$template->assign(array(
    'authenticated' => $user->is_authenticated(),
    'membername' => $_SESSION['membername']
));
$template->display("user.main.tpl");
$template->display("firsttimeinfo.tpl");
$template->display("infovis.tpl");
$template->assign(array(
    'authenticated' => $user->is_authenticated()
));

if ($user->is_authenticated()) {
    if (is_null($user->user['sonof'])) {
        $template->assign(array("user_not_connected" => true,
            "userimage" => (empty($user->user['profilepic']) ? "common.png" : $user->user['proilepic'])));
    }
} else {
    $template->assign(array("userimage" => "common.png"));
}




$template->display("right-container.tpl");
$template->display("login.form.tpl");
$template->display('search.form.tpl');
$template->display("feedback.form.tpl");
$template->display('operations.tpl');
if ($user->is_authenticated()) {
    $template->display('operations.add.form.tpl');
    $template->display('operations.remove.tpl');
    $template->display("suggest.tpl");
}
?>
