<?php

/**
 * APP GLOBAL CONFIG VARIALBE
 **/

// DEBUG MODE
error_reporting(E_ALL);
ini_set("display_errors", 1);


// DATE TIMEZONE
date_default_timezone_set("Asia/Manila");
define("DATE", date("Y-m-d"));
define("TIME", date("H:i:s"));
define("DATE_TIME", date("Y-m-d H:i:s"));


// DOMAIN ROOT1
define("DOMAIN", "//" . $_SERVER["SERVER_NAME"]);


// INCLUDE SETTINGS
require_once("settings.php");
