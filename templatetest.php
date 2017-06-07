<?php

require_once 'header.php';

global $template;

$template->header();
$template->assign('approvedonly', true);
$template->display('suggest.add.tpl');
$template->footer();