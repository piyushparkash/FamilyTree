<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Handles all the output of the page
 * @extends Smarty
 * @author piyush
 */

require 'libs/Smarty.class.php';
class template extends Smarty {
    
    //The Constructor
    function __construct() {
        parent::__construct(); //Calls the smarty constructor
        $this->setCacheDir("cache/");
        $this->setCompileDir("compile/");
        $this->setTemplateDir("../html/");
        
        //Check here for the permissions of the above folder and report error
    }
}

?>
