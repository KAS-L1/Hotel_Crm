<?php require("jwt.php") ?>
<?php

if (isset($_COOKIE['_xsrf-token'])) {

    $token = $_COOKIE['_xsrf-token'];

    // Verifying a token
    $jwt = new JWT('this-is-secure-secret-key');
    $payload = $jwt->verifyToken($token);
    if ($payload) {

        $user = $DB->SELECT_ONE_WHERE("users", "*", ['user_id' => $payload['user_id']]);

        define('AUTH_USER_ID', $user['user_id']);
        define('AUTH_USER', $user);

    }else{
        setcookie("_xsrf-token", "", time() - 1, "/");
        session_destroy();
        redirect("/403?res=2");
    }

}else{
    redirect("/403?res=1");
}