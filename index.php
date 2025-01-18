<?php require_once("app/init.php") ?>

<?php

/**
 * MAIN APPLICATION
 **/

// GET THE INDEX PAGE
$PAGE = PAGE();

// REDIRECT TO DEFAULT PAGE
if($PAGE == "index") redirect("login");

// PUBLIC ROUTE ****************************************

switch ($PAGE) {
    case "home":
        die(include_once("public/Home.php"));
        break;
    case "login":
        die(include_once("public/Login.php"));
        break;
    case "register":
        die(include_once("public/Register.php"));
        break;
    case "forgot":
        die(include_once("public/Forgot.php"));
        break;
    case "recover":
        die(include_once("public/Recover.php"));
        break;
}

// PROTECTED ROUTE ****************************************

require_once("api/auth/auth.php"); // AUTHENTICATED USER
// require_once("api/app/app.php"); // APPLICATION FUNCTIONS
require_once("page/_component/app.php"); // APPLICATION COMPONENTS

include_once("page/_template/Header.php");

if (VIEW("page/", $PAGE) == "404") {
    include_once("page/404.php");
} else {
    include_once(VIEW("page/", $PAGE));
}

include_once("page/_template/Footer.php");
