<?php
/**
 * Created by PhpStorm.
 * User: Piyush
 * Date: 7/16/2017
 * Time: 1:29 AM
 */

require 'vendor/autoload.php';

//Initialize the hybridauth


$haConfig = [
    "callback" => "http://familytree.ratupar.in/google.php",
    "keys" => array("id" => "454065471844-hbhs6c082mfblptqs5ohhoaji1c796tq.apps.googleusercontent.com", "secret" => "XMdR7JNAoh1ara-2xP5QM2mn"),
    "debug_mode" => true,
    // to enable logging, set 'debug_mode' to true, then provide here a path of a writable file
    "debug_file" => __DIR__ . "/auth.log",
];


$adapter = new Hybridauth\Provider\Google($haConfig);

$adapter->authenticate();

$profile = $adapter->getUserProfile();

var_dump($profile);


//if (isset($_REQUEST['hauth_start']) || isset($_REQUEST['hauth_done'])) {
//    HybridEndpoint::process();
//}
