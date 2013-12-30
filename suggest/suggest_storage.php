<?php

/**
 * Class defined to store a suggest and all the info required by the suggest
 *
 * @author piyush
 */
/**
 * Defining all the Fields that can be altered
 */
define("NAME", "membername");
define("DOB", "dob");
define("ALIVE", "alive");
define("GENDER", "gender");
define("RELATIONSHIP", "relationship_status");
define("GAON", "gaon");
define("ADDMEMBER", "addmember");
define("ADDSPOUSE", "addspouse");
define("DELMEMBER", "delmember");

//Define the suggest types
define("ADD", "add");
define("DEL", "del");
define("MODIFY", "modify");

class suggest_storage {

    /**
     *
     * @var string Unique name of the suggest
     */
    public $name;

    /**
     *
     * @var string The filename of the template file to be used with this suggest
     */
    public $tpl;

    /**
     *
     * @var array Array containing all the parameters to be used by the template
     */
    public $parameter;

    /**
     *
     * @var string This will tell us which type of suggest is it (add/delete/modify)
     */
    public $type;

    public function __construct($sname, $stpl, $sparameter) {
        $this->add($sname, $stpl, $sparameter);
    }

    /**
     * This function is used to set the suggest parameters that are required
     * @param string $sname
     * @param string $stpl
     * @param array $sparameters
     */
    public function add($sname, $stpl, $sparameter) {
        $this->name = $sname;
        $this->tpl = $stpl;
        $this->parameter = $sparameter;
    }

}

?>
