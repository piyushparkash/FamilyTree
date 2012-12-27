<?php

/**
 * Class that manipulates Members
 *
 * @author piyush
 */
require_once 'member_operation.php';

class member extends member_operation {

    public $data;

    public function __construct($memberid) {
        parent::__construct($memberid);
        $this->populate_data($memberid);
    }

    function getparent() {
        return new member($this->data['sonof']);
    }

    function get_sons() {
        global $db;
        $finalarray = array();
        $query = $db->query("select * from member where sonof=$this->id");
        while ($row = $db->fetch($query)) {
            array_push($finalarray, new member($row['id']));
        }
        return $finalarray;
    }

    function has_sons() {
        global $db;
        $query = $db->query("select count(*) as nosons from member where sonof=$this->id");
        $row = $db->fetch($query);
        return $row['nosons'];
    }

    function populate_data($memberid) {
        // Fill user variable with user data
        global $db;
        $query = $db->query("Select * from member where id=$memberid");
        $row = $db->fetch($query);
        $this->data = $row;
    }

}

?>
