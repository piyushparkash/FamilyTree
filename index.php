<?php

require 'header.php';
//If config.php doesn't exist, probably not installed
if (!file_exists("config.php")) {
    require_once("install/install.php");
    $install = new install();
    exit();
}

//Now that the things are installed
global $db, $template;

//Check if config.php is readable if not then tell user to set the permissions manually
if (!is_readable("config.php")) {
    $template->header();
    $template->display("config.error.tpl");
    exit();
}

// Global variable to test if user has filled registration form
$posted = false;

// If pressed submit button
if (isset($_POST['register_submit'])) {
    //Make global variable true
    $posted = true;
    //get all the posted info
    $id = $_POST['register_id'];
    $username = $_POST['register_username'];
    $password = $_POST['register_password'];
    //convert date into time stamp
    preg_match("/([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4,4})/", $_POST['register_dob'], $matches);
    $dob = mktime(0, 0, 0, $matches[2], $matches[1], $matches[3]);
    $gender = $_POST['register_gender'];
    $relation = $_POST['register_relationship'];
    $gaon = $_POST['register_gaon'];
    $email = $_POST['register_email'];
    $about = $_POST['register_about'];

    //Generate a token key for activation
    $token = generate_token();

    //Prepare the sql statement
    $sql = "update member set username='$username',password='$password',dob=$dob,gender=$gender,relationship_status=$relation,gaon='$gaon',
	emailid='$email',alive=1,aboutme='$about',joined=" . time() . ",tokenforact='$token' where id=$id";
    // If successful query then
    if (executequery($sql)) {

        // Mail user for activation and Mail confirmation
        vanshavali_mail($email, "Welcome to Vanshavali | Email Confirmation", "
						<html>
						<body>
						<h3 align='center'>Welcome to Family!</h3><br>
						Hi,<br>
						We welcome you to the family. Please click  on the link below to confirm your email address.
						Here are your details:<br>
						Username:$username<br>
						Password:********<br>
						<a href='www.vanshavali.co.cc/activate.php?token=$token&emailid=$email'>Click here to activate your account</a>
						<br><br>
						Thanks, Keep Visiting<br>
						Admin, Vanshavali.co.cc
						</body>
						</html>
						");
    } else {
        die("Some error occurred.Please try again");
    }
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
$template->display("right-container.tpl");
$template->display("login.form.tpl");
?>
