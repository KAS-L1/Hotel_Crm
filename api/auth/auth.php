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
        die("401 Unauthorized Access");
    }

}else{
    die("403 Forbidden Access");
}