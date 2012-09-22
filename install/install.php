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
        global $template, $config, $db;
        $sub = ($sub == null) ? 1 : $sub;
        if ($sub == 1) {
            $template->display("install.ask_database_details.tpl");
        } elseif ($sub == 2) {
            $host = $_POST['database_host'];
            $username = $_POST['database_username'];
            $password = $_POST['database_password'];
            $database = $_POST['database_name'];

            //Connect to database
            $db->connect($host, $username, $password);

            //Create Database
            $db->query("CREATE DATABASE $database");

            //Select The given database
            $db->select_db($database);

            //Setup basic database
            $this->setup_database();

            //Now create the config.php file save it
            $file = fopen("config.php", "w+");
            $data = '<?php\n$config["host"]=' . $host .
                    ';\n$config["username"]=' . $username .
                    ';\n$config["password"]=' . $password .
                    ';\n$config["database"]=' . $database .
                    ';\n?>';

            fwrite($file, $data);
            fclose($file);

            $template->display("database_success.tpl");
        }
    }

    /**
     * Function to setup the database
     */
    private function setup_database() {
        //Read the basic table schema and execute it
        //now enter the data that we have
    }

}

?>
