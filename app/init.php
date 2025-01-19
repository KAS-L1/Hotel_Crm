<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * APPLICATION SETUP
**/

require_once("core/config.php");

require_once("core/functions.php");

require_once("core/utils.php");

require_once("core/components.php");

// DATABASE INSTANCE
require_once("core/database.php");
$DB = new Database();
