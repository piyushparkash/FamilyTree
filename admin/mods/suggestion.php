<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of suggestion
 *
 * @author piyush
 */
class suggestion {

    protected $templateholder, $database;
    
    function __construct($getvars, $template, $db) {
        //Assign template holder
        $this->templateholder = $template;
        $this->database = $db;

        switch($getvars['submode'])
        {
            case "allsuggestion":
            default :
                //Get all the suggestions
                $query = $this->database->query("select * from suggested_info");
                $all_suggest = $this->database->fetch_all($query);
                
                //prettify the data before showing it to user
                $this->prettifySuggest($all_suggest);
                
                
                $this->templateholder->assign('results', $all_suggest);
                $this->templateholder->display('admin/admin.suggestion.allsuggestion.tpl');
                break;
        }
    }
    
    public function prettifySuggest(&$allsuggest)
    {
        //We get the raw data of suggested_info table
        //We need to prettify to show the user
        foreach ($allsuggest as &$suggest) {
            
            //Change check the suggested by and suggested_to column
            $suggest['suggested_by'] = $this->getNameforID($suggest['suggested_by']);
            $suggest['suggested_to'] = $this->getNameforID($suggest['suggested_to']);
            
            $suggest['approved'] = ($suggest['approved'] == 0) ? "No" : "Yes";
            
            //show proper time to the user
            $suggest['ts'] = date("D d/F/Y g:i:s A", $suggest['ts']);
        }
    }
    
    
    public function getNameforID($id)
    {
        $name = $this->database->get("select membername from member where id = $id");
        
        return $name['membername'];
    }
    
}
