<?php

/**
 * @author lolkittens
 * @copyright 2012
 */

//include Header files and connect to database
require ("../header.php");
connecttodatabase();

//user authenticated?
if (is_authenticated()) {
    switch ($_POST['type']) {
        case "child": //if type child

            //fill array with data
            $finalarray['name'] = $_POST['name'];
            $finalarray['gender'] = $_POST['gender'];
            $finalarray['sonof'] = $_POST['sonof'];

            //put in the database
            //echo "insert into suggested_info (typesuggest,suggested_value,suggested_by,ts) values('child', '" .
              //  json_encode($finalarray) . "'," . $_COOKIE['id'] . "," . time();
            executequery("insert into suggested_info (typesuggest,suggested_value,suggested_by,ts) values('child', '" .
                json_encode($finalarray) . "'," . $_COOKIE['id'] . "," . time().")");

            break;
        case "dob": //if type dob
            break;
        case "relation": //if type relation
            break;
        case "alive":
            break;
        default: //when no option matches
            break;


    }
}
?>