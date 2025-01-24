<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');
// ob_start();
require_once realpath('../../vendor/autoload.php');
require_once realpath('../app/router.php');
require_once realpath('../app/model.php');
require_once realpath('../app/controller.php');
require_once realpath('../app/view.php');
/*
$out = ob_get_contents();
ob_end_clean();
echo $out;
*/
