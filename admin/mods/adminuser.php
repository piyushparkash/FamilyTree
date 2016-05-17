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
                $query = $this->database->query("select * from member;");
                $all_member = $this->database->fetch($query);
                // assign your db results to the template
                $this->templateholder->assign('results', $this->paginate_db($all_member));
                // assign {$paginate} var
                $this->templateholder->paginate->assign($this->templateholder);
                // display results
                $this->templateholder->display('admin/user.allregistered.tpl');
                break;
        }
    }

    function paginate_db($array) {
        $this->templateholder->paginate->setTotal(count($_data));
        return array_slice($array, $this->templateholder->paginate->getCurrentIndex(), $this->templateholder->paginate->getLimit());
    }

}
