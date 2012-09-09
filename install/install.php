<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Script to install vanshavali
 *@package vanshavali
 * @author piyush
 */
include '../header.php';
class install {
    /*
     * main function to install to initiate vanshavali installation
     */
    function install()
    {
        //make sure to run this only if database is not installed
        //So check if database is installed
        if (!empty($config) and file_exists("../config.php"))
        {
            //its installed! Return to index.php
            header("Location:../index.php");
        }
        
        //Reached here huh? Installtion will begin now
        //First main thing - ask for name of database
        $this->ask_database_name();
        
    }
    /**
     * Asks for database name from the user where to install vanshavali
     * 
     */
    function ask_database_name()
    {
        global $template;
        $template->display("install.ask_database_details.tpl");
    }
    
    /**
     * Function to setup the database
     */
    function setup_database($host,$username,$password)
    {
        //Read the basic table schema and execute it
        //now enter the Previous data that we have
        
    }
    
}

?>
