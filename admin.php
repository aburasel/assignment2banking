<?php
//require_once "app/autoload.php";

use App\src\AdminApp;

require_once "vendor/autoload.php";

$cliApp= new AdminApp();
$cliApp->run();