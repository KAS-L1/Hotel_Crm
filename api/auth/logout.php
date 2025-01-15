<?php
session_start();
setcookie("_xsrf-token", "", time() - 1, "/");
session_destroy();
die(header("Location: ../../login?res=logout"));
