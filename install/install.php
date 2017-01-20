<?php

/**
 * This class contains function to install Family Tree
 * @package install
 * @author piyush
 */
require_once __DIR__ . '/../functions.php';


$mode = @$_GET['mode'];
$sub = @$_GET['sub'];

class install {

    /**
     * This function is used to start the installation.
     * @global string $mode
     * @global integer $sub
     * @return null
     */
    function install($base) {
        global $mode, $sub;
        //make sure to run this only if database is not installed
        //So check if database is installed
        if (!empty($config) and file_exists("../config.php")) {
            //its installed! Return to index.php
            header("Location:../index.php");
        }

        //Reached here huh? Installtion will begin now
        //Check mode and perform actions
        switch ($mode) {
            //All the other option will be above ask_database_name as it is also the default


            case "ask_database_name":
                $this->ask_database_name($mode, $sub);
                break;
            case "setupAdmin":
                $this->setupAdmin($mode, $sub);
                break;
            case "check_wp_login":
                $this->check_wp_login($mode, $sub);
                break;
            case "check_directory_permission":
            default :
                $this->check_directory_permission($mode, $sub);
                break;
        }
    }

    /**
     * This function is used to check the required directories permission
     * during the installation as FamilyTree needs to create some config files
     * which can only be possible if he has the permission
     * @global \template $template
     * @param string $mode
     * @param integer $sub
     */
    function check_directory_permission($mode, $sub) {
        global $template;
        $cache = false;
        $compile = false;
        $main = false;

        //Check if directories are writable
        //Template Cache directory
        if (dir_iswritable("template/cache")) {
            $cache = TRUE;
            $template->assign("cache", true);
        } else if (!file_exists("template/cache")) {
            //try making the directory if possible
            @mkdir("template/cache", 0755);
        }

        //Template compile directory
        if (dir_iswritable("template/compile")) {
            $compile = true;
            $template->assign("compile", true);
        } else if (!file_exists("template/compile")) {
            //Trying to make the directory myself here
            @mkdir("template/cache", 0755);
        }

        //Family Tree main directory
        $template->assign("dir", "config.php");
        if (dir_iswritable(".")) {
            $main = true;
            $template->assign("main", TRUE);
        }

        if ($cache && $compile && $main) {
            header("Location: index.php?mode=ask_database_name");
        }

        //Apparently we cannot use template as we have not write permission in compile directory
        //So read the tpl file and output it as it is.
        // We are using @ just in case we don't have permission to read
        $output = file_get_contents("html/install.directory_check.tpl");

        if ($output === false) {
            //We also cannot read that file so just print a simple plain message
            echo "Please give permission to the root folder i.e. ./FamilyTree <br /> The template cache folder (template/cache) <br /> The template compile folder (template/compile) and refresh this page";
            return false;
        }

        echo $output;

        if (!$cache) {
            echo "<h4>Cannot write in template/cache folder</h4><br>";
        }
        if (!$compile) {
            echo "<h4>Cannot write in template/compile folder</h4><br>";
        }
        if (!$main) {
            echo "<h4>Cannot write in FamilyTree's Directory</h4><br>";
        }
    }

    /**
     * 
     * @global \template $template
     * @global \db $db
     * @param type $mode
     * @param type $sub
     */
    function setupAdmin($mode, $sub) {
        global $template, $db, $vanshavali, $user;
        $sub = ($sub == null) ? "firstfamily" : $sub;

        if ($sub == "firstfamily") {
            $template->header();
            $template->display("family.form.tpl");
            $template->footer();
        } else if ($sub == "firstfamilypost") {
            //This just means that user has submitted the firstfamily form

            $vanshavali->addfamily($_POST['family_name']) or trigger_error("Unable to add family. Please try again");


            //We have added the first family. Lets proceed to add first member
            header("Location:index.php?mode=setupAdmin&sub=firstmember");
            
        } else if ($sub == "firstmember") {
            //This is where we find out that there is no member installed
            //We need to first get the family that was just added for this member
            $family = $db->get("select * from family limit 1");


            //We would have got information about wordpress user if wordpress is enabled
            if ($vanshavali->wp_login) {

                //We have WP Enabled
                $template->assign("id", $_SESSION['wpid']);
            }


            $template->header();
            $template->assign("is_admin", 1);
            $template->assign("is_wordpress_enabled", $vanshavali->wp_login);
            $template->assign("familyid", $family['id']);
            $template->display("register.form.tpl");
            $template->footer();
        }
    }

    function check_wp_login($mode, $sub) {
        global $template, $db, $user, $vanshavali;

        //Calculate the callback to be used
        $callback = $vanshavali->hostname . CALLBACK;

        if (!$vanshavali->wp_login) {
            header("Location:index.php?mode=setupAdmin");
            return;
        }

        $sub = ($sub == null) ? 1 : $sub;

        if ($sub == 1) {
            $template->header();
            $template->assign("callback", $callback);
            $template->display("install.check_wp_login.tpl");
            $template->footer();
            return;
        } elseif ($sub == 2) {
            if (!$user->oauth->check_callback($callback)) {
                //Callback is not proper as the request didn't go through
                $template->header();
                $template->assign("callback", $callback);
                $template->assign(array("error" => 1,
                    "message" => "We are not able to make request to Wordpress. Can you please check the Callback and set it to the below mentioned URL"));
                $template->display("install.check_wp_login.tpl");
                $template->footer();
            } else {
                //Complete the other WP workflow here only.
                $_SESSION['redirect_to'] = "index.php?mode=setupAdmin";
                header("Location:oauthlogin.php");
            }
        }
    }

    function get_wp_vars($endpoint) {

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $output = curl_exec($ch);

        curl_close($ch);

        if (!$output) { //if curl request fails
            return false;
        }

        $wpapi_vars = json_decode($output, true);

        return array("namespace" => $wpapi_vars["namespaces"][0]);
    }

    /**
     * This function is used ask the user about the database details
     * @param string $mode Describes which phase is currently running
     * @param string $sub Describes which part is running of the phase
     * @return null
     */
    function ask_database_name($mode, $sub) {
        global $template, $db;

        $sub = ($sub == null) ? 1 : $sub;

        if ($sub == 1) {
            $template->header();
            $template->display("install.ask_database_details.tpl");
        } elseif ($sub == 2) {
            $host = $_POST['database_host'];
            $username = $_POST['database_username'];
            $password = $_POST['database_password'];
            $database = $_POST['database_name'];
            $adminEmail = $_POST['admin_email'];
            $consumerKey = $_POST['consumer_key'];
            $consumerKeySecret = $_POST['consumer_key_secret'];
            $auth_endPoint = $_POST['auth_end_point'];
            $access_endPoint = $_POST['access_end_point'];
            $endpoint = $_POST['end_point'];
            if (empty($host) ||
                    empty($username) ||
                    empty($database) ||
                    empty($adminEmail)) {

                $template->header();
                $template->assign(array("error" => 1,
                    "message" => "Form not completed. Please complete the form"));
                $template->display("install.ask_database_details.tpl");
                return;
            }


            $wp_vars = array();
            if (!empty($consumerKey) || !empty($consumerKeySecret) || !empty($endpoint) || !empty($access_endPoint) || !empty($auth_endPoint)) {
                //If even one of them is empty
                if (empty($consumerKey) || empty($consumerKeySecret) || empty($endpoint) || empty($access_endPoint) || empty($auth_endPoint)) {
                    $template->header();
                    $template->assign(array("error" => 1,
                        "message" => "Incomplete OAuth Details. Please provide all information."));
                    $template->display("install.ask_database_details.tpl");
                    return;
                } else {
                    $wp_vars = $this->get_wp_vars($endpoint);
                    if (!$wp_vars) {
                        $template->header();
                        $template->assign(array("error" => 1,
                            "message" => "Could not reach endPoint mentioned. Please check and retry"));
                        $template->display("install.ask_database_details.tpl");
                        return;
                    }
                }
            }



            //Connect to database
            $db->connect($host, $username, $password);

            //Create Database
            $db->query("CREATE DATABASE if not exists $database");

            //Select The given database
            $db->select_db($database);

            //Setup basic database
            $this->setup_database();

            //Now create the config.php file save it
            $file = fopen("config.php", "w+");
            if (!$file) {
                trigger_error("Error opening or creating config.php file", E_USER_ERROR);
            }

            $filedata = array(
                'host' => $host,
                'username' => $username,
                'password' => $password,
                'database' => $database,
                'admin_email' => $adminEmail,
                'hostname' => getFullURL(),
                'consumer_key' => $consumerKey,
                'consumer_key_secret' => $consumerKeySecret,
                'end_point' => $endpoint,
                'auth_end_point' => $auth_endPoint,
                'access_end_point' => $access_endPoint
            );


            if (!empty($wp_vars)) {
                $filedata = $filedata + $wp_vars;
            }

            $data = "<?php\n\$config = " . var_export($filedata, true) . ";";

            fwrite($file, $data);
            fclose($file);

            //Set file permission to 0644, Never leave this 0
            if (!chmod("config.php", 0644)) {
                //Read and write for the owner and read for everyone else
                trigger_error("Cannot set config.php permissions", E_USER_ERROR);

                //Check if permissions have been successfull applied or not
                if (!is_readable("config.php")) {
                    trigger_error("Wrong config.php permissions. Please give config.php file 644 permission. <br> Use the Following command<br>$ chmod 644 config.php", E_USER_ERROR);
                }
            }

            $template->display("database_success.tpl");
        }
    }

    /**
     * This is a private function used to setup the database
     * @global \db $db
     */
    private function setup_database() {
        global $db;
        //Install the tables

        if (!$this->installTables()) {
            trigger_error("Cannot create Tables", E_USER_ERROR);
        }
    }

    /**
     * This function is used install the tables in the database
     * Returns true if all the tables were installed successfully else false
     * @global \db $db Ths instance of the db class
     * @return boolean 
     */
    private function installTables() {
        global $db;
        $family = $db->query("Create table if not exists family (
            id int(11) primary key auto_increment,
            family_name mediumtext not null,
            ts int(11) not null )");


        $member = $db->query("create table if not exists member (
            id int(11) null primary key auto_increment,
            membername mediumtext not null,
            username mediumtext default null,
            `password` mediumtext default null,
            sonof int(11) null default null,
            profilepic varchar(255) default 'common.png',
            dob int(11) default null,
            gender int(1) default 0,
            relationship_status int(11) default 0,
            gaon mediumtext default null,
            related_to int(11) null default null,
            emailid varchar(256) default null unique,
            alive int(1) default 0,
            aboutme longtext default null,
            lastlogin int(11) default null,
            joined int(11) default null,
            approved int(1) default 0,
            tokenforact text default null,
            dontshow int(1) default 0,
            family_id int(11) null default null,
            foreign key (family_id) references family(id),
            foreign key (related_to) references member(id),
            admin int(1) default 0,
            wordpress_user int(11) default null unique )");

        $feedback = $db->query("create table if not exists feedback (
            id int(11) not null primary key auto_increment,
            user_name text not null,
            user_emailid text not null,
            feedback_text text not null,
            seen int(1) default 0 );");

        $joinrequest = $db->query("create table if not exists joinrequest (
            id int(11) not null primary key auto_increment,
            formember int(11) not null,
            pic text default null,
            personalmessage text default null,
            emailid text not null,
            tokenforact varchar(20) default null,
            approved int(1) default 0,
            foreign key(formember) references member(id) );");

        $suggested_info = $db->query("create table if not exists suggested_info (
            id int(11) not null primary key auto_increment,
            typesuggest mediumtext not null,
            new_value text default null,
            old_value text default null,
            suggested_by int(11) not null,
            suggested_to int(11) not null,
            ts int(11) not null,
            approved int(1) default 0,
            foreign key(suggested_by) references member(id),
            foreign key(suggested_to) references member(id) );");

        $suggest_approved = $db->query("create table if not exists suggest_approved (
            id int(11) not null primary key auto_increment,
            suggest_id int(11) not null,
            user_id int(11) not null,
            action int(2) not null,
            foreign key (suggest_id) references suggested_info(id),
            foreign key (user_id) references member(id) );");

        //Commenting this out as we have ask user about the new family from now on.
        //$dasfamily = $db->query("insert into family (family_name,ts) values('Das Family'," . time() . ");");
        //Now the data that we already have
        //Commenting this out, as we don't have any default family data from now on.
        //$memberdata = file_get_contents("member_data.sql");
        //$memberdata_sql = $db->query($memberdata);

        return $member && $feedback && $joinrequest && $suggested_info && $suggest_approved /* && $memberdata_sql */ && $family /* && $dasfamily */;
    }

}
