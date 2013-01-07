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
        $this->autofix();
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

    function autofix() {
        global $db;
        $nosons = $this->has_sons();
        if ($nosons > 0) {
            // If the memeber has sons Change the status to married
            $this->set_relationship(1);
        }
    }

    function set_relationship($relationship_id) {
        global $db;
        if (!$db->query("update member set relationship_status=$relationship_id where id=$this->id")) {
            return false;
        } else {
            return true;
        }
    }

}

?>
