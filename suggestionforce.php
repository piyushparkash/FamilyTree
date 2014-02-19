<?php

// put your code here

require_once __DIR__.'/header.php';
global $suggest_handler;

$template->header();

//Show all the Suggestions
$suggest_handler->getsuggestions(true);

$template->footer();
?>