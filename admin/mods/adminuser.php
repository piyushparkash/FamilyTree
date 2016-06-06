<?php

class adminuser {

    protected $templateholder, $database;

    function __construct($getvars, $template, $db) {

        //Assign template holder
        $this->templateholder = $template;
        $this->database = $db;

        switch ($getvars['submode']) {

            case "allregistered":
            default:
                $query = $this->database->query("select * from member where username!='' and password!='';");
                $all_member = $this->database->fetch_all($query);
                // assign your db results to the template
                $this->templateholder->assign('results', $all_member);
                // display results
                $this->templateholder->display('admin/adminuser.allregistered.tpl');
                break;
        }
    }

}
