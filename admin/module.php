<?php

/**
 * Description of module
 *
 * @author piyush
 */
class module {

    protected $getvars;
    protected $template;
    protected $db;

    public function __construct($getarray, $template, $db) {

        //Parse the mode
        $mode = $getarray['mode'];

        //There should be a file named with this mode
        $mod_file = $mode . ".php";

        //This file should be present in mod folder
        if (file_exists($mod_file)) {
            trigger_error("Cannot load the $mode module");
        }

        require_once __DIR__ . "/mods/$mod_file";

        //Initialize the given module
        $active_mod = new $mode($getarray, $template, $db);
    }

}
