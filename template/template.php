<?php
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
        $this->setCacheDir("cache");
        $this->setCompileDir("compile");
        $this->setTemplateDir("html");
        
        //Check here for the permissions of the above folder and report error
    }
    
    /**
     * Function to show the global header
     * @return \template
     */
    function header()
    {
        $this->display("header.tpl");
        return $this;
    }
    
    /**
     * Function to show the global footer
     * @return \template 
     */
    function footer()
    {
        $this->display("footer.tpl");
        return $this;
    }
}

?>
