<?php
/**
 * Created by PhpStorm.
 * User: Piyush
 * Date: 7/16/2017
 * Time: 1:46 PM
 */

require 'vendor/autoload.php';

//Initialize the hybridauth


$haConfig = [
    "callback" => "http://fttest.ratupar.in/facebook.php",
    "keys" => array("id" => "102200720418637", "secret" => "71da9c34a0ea21207cee410d14b0bcb3"),
    "debug_mode" => true,
    // to enable logging, set 'debug_mode' to true, then provide here a path of a writable file
    "debug_file" => __DIR__ . "/auth.log",
];


$adapter = new Hybridauth\Provider\Facebook($haConfig);

$adapter->authenticate();

$profile = $adapter->getUserProfile();

var_dump($profile);
