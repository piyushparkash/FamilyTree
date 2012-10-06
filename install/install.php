<?php

/**
 * Script to install vanshavali
 * @package install
 * @author piyush
 */
$mode = @$_GET['mode'];
$sub = @$_GET['sub'];

class install {
    /*
     * main function to install to initiate vanshavali installation
     */

    function install() {
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
            default:
                $this->ask_database_name($mode, $sub);
                break;
        }
    }

    /**
     * Asks for database name from the user where to install vanshavali
     * @param string $mode Describes which phase is currently running
     * @param string $sub Describes which part is running of the phase
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

            if (empty($host) || empty($username) || empty($password) || empty($database)) {
                $template->header();
                $template->assign(array("error" => 1,
                    "message" => "Form not completed. Please complete the form"));
                $template->display("install.ask_database_details.tpl");
                return;
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
            if (!$file)
            {
                trigger_error("Error opening or creating config.php file",E_USER_ERROR);
            }
            
            $data = "<?php\n\$config['host']=$host
                    ;\n\$config['username']=$username
                    ;\n\$config['password']=$password
                    ;\n\$config['database']=$database
                    ;\n?>";

            $wr=fwrite($file, $data);
            fclose($file);
            
            //Set file permission to 644
            if (!chmod("config.php", 644))
            {
                //Read and write for the owner and read for everyone else
                trigger_error("Cannot set config.php permissions",E_USER_ERROR);
                
                //Check if permissions have been successfull applied or not
                if (!is_readable("config.php"))
                {
                trigger_error("Wrong config.php permissions. Please give config.php file 644 permission. <br> Use the Following command<br>$ chmod 644 config.php",E_USER_ERROR);
                }
            }

            $template->display("database_success.tpl");
        }
    }

    /**
     * Function to setup the database
     */
    private function setup_database() {
        global $db;
        //Read the basic table schema and execute it
        $scheme_file = fopen("schema.sql", "r");
        $data = fread($scheme_file, filesize("schema.sql"));
        
        //$db->query($data);
        
        fclose($scheme_file);
        
        //now enter the data that we have
        $member_data = fopen("member_data.sql", "r");
        $data = fread($member_data, filesize("member_data.sql"));
        //$db->query($data);
        fclose($member_data);
    }

}

?>
