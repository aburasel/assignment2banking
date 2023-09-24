<?php
//require_once "app/autoload.php";

use App\src\CustomerApp;

require_once "vendor/autoload.php";

$customerApp= new CustomerApp();
$customerApp->run();