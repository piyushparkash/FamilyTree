<?php

require 'header.php';
//If config.php doesn't exist, probably not installed
if ((!file_exists("config.php"))) {
    require_once(__DIR__ . "/install/install.php");
    $install = new install(getFullURL());
    exit();
} else if (vanshavali::firstTime() or vanshavali::firstTimeFamily()) {
    //installation still imcomplete
    require_once(__DIR__ . "/install/install.php");
    $install = new install(getFullURL());
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

//Check for wordpress id and redirect if not
if (vanshavali::$wp_login && $user->is_authenticated()) {
    $wpid = $user->user['wordpress_user'];

    if (empty($wpid)) {
        $_SESSION['redirect_to'] = "index.php?set_wordpress_id=true";
        header("Location:oauthlogin.php");
    }
}

//If they have come back from the page with the wpid
if (vanshavali::$wp_login && $_GET['set_wordpress_id'] == "true") {
    $current_user = vanshavali::getmember($_SESSION['id']);
    $current_user->set("wordpress_user", $_SESSION['wpid']);
}


//Display the header and basic contents
$template->header();
$template->assign(array(
    'authenticated' => $user->is_authenticated(),
    'membername' => $_SESSION['membername']
));

// $template->display("user.main.new.tpl");
//$template->display("infovis.tpl");
$template->assign(array(
    'authenticated' => $user->is_authenticated()
));

if ($user->is_authenticated()) {
    if (is_null($user->user['sonof'])) {
        $template->assign(array("user_not_connected" => true,
            "userimage" => (empty($user->user['profilepic']) ? "common.png" : $user->user['proilepic'])));
    } else {
        $template->assign("id", $user->user['id']);
        $template->display("showuser.tpl");
    }
} else {
    $template->assign(array("userimage" => "common.png"));
}


//Show this when the user has arrived from change password page
if ($_GET['passwordchanged']) {
    $template->display("forgotpassword.success.tpl");
}


$template->display("newlayout.tpl");
//Check if all the members have wordpress ID
if ($user::check_all_wordpress()) {
    $template->assign("wp_login", vanshavali::$wp_login);
}
else
{
    $template->assign("wp_login", false);
}

$template->display("login.form.tpl");
$template->display('forgotpassword.modal.tpl');
$template->display('search.form.tpl');
$template->display("feedback.form.tpl");
if ($user->is_authenticated()) {
    $template->display('operations.add.form.tpl');
    $template->display('operations.remove.tpl');
    $template->display('operations.edit.tpl');
    $template->display('operations.addspouse.tpl');
    $template->display('operations.addparents.form.tpl');
    $template->display("suggest.tpl");
}
