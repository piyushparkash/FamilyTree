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
function ajaxSuccess($data = NULL) {
    echo json_encode(array("success" => 1, "data" => $data));
}

/**
 * This function is used to signal the front end that the ajax Request was
 * not successfull and has failed
 * @param array|string $data Any additional data that needs to be sent with the
 * the signal
 */
function ajaxError($data = NULL) {
    echo json_encode(array("success" => 0, "data" => $data));
}

/**
 * This function is used to check if we permission to perform write operation
 * on the given directory. Returns True if Yes else False
 * @param string $dirname The name of directory whose permissions is to be 
 * checked with ending '/'
 * @return boolean
 */
function dir_iswritable($dirname) {
    $testfile = fopen($dirname . "/test_lock_file", "w+");

    //If it was not able to open the file, that simply means we don't have the
    //permission to open it
    if ($testfile === false) {
        return false;
    }
    //If file was created then we have the permission
    else {
        unlink($dirname . "/test_lock_file");
        return true;
    }
}

/**
 * 
 * @return type
 */
function getFullURL() {
    $base_dir = __DIR__; // Absolute path to your installation, ex: /var/www/mywebsite
    $doc_root = preg_replace("!${_SERVER['SCRIPT_NAME']}$!", '', $_SERVER['SCRIPT_FILENAME']); # ex: /var/www
    $base_url = preg_replace("!^${doc_root}!", '', $base_dir); # ex: '' or '/mywebsite'
    $protocol = empty($_SERVER['HTTPS']) ? 'http' : 'https';
    $port = $_SERVER['SERVER_PORT'];
    $disp_port = ($protocol == 'http' && $port == 80 || $protocol == 'https' && $port == 443) ? '' : ":$port";
    $domain = $_SERVER['SERVER_NAME'];
    $full_url = "${protocol}://${domain}${disp_port}${base_url}";

    return $full_url;
}

?>
