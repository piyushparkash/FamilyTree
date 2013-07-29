<?php

/**
 * This file contains all the general function used through out Family Tree
 * @author Piyush Parkash
 */

/**
 * This function is used to seperate the extention and the filename of a file
 * @param string $filename The name of the file
 * @param boolean $ext Set to true if want to get the extension
 * @return string
 */
function fileext($filename, $ext = true) {
    $filename = basename($filename);
    $arr = explode(".", $filename);
    $count = count($arr);
    if ($ext) {
        return $arr[$count - 1];
    } else {
        return implode("", array_slice($arr, 0, $count - 1));
    }
}

/**
 * This function is used to signal the front end that ajax Request was successful
 * @param array|string $data Any additional data that needs to be sent with the signal (optional)
 */
function ajaxSuccess($data=NULL)
{
    echo json_encode(array("success" => 1, "data" => $data));
}

/**
 * This function is used to signal the front end that the ajax Request was
 * not successfull and has failed
 * @param array|string $data Any additional data that needs to be sent with the
 * the signal
 */
function ajaxError($data=NULL)
{
    echo json_encode(array("success" => 0, "data" => $data));
}
?>
