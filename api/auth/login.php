<?php require("../../app/init.php") ?>
<?php require("jwt.php") ?>
<?php

csrfProtect('verify');

// echo "Session Token: " . $_SESSION['csrf_token'] . "<br>";
// echo "Request Token: " . $_POST['csrf_token'] . "<br>";

if (!isset($_POST['username']) or !isset($_POST['password'])) die(toast("error", "Invalid server request"));

$username = $DB->ESCAPE(VALID_STRING($_POST['username']));
$password = $DB->ESCAPE(VALID_PASS($_POST['password']));

if (empty($username) or empty($password)) die(toast("error", "Username or password cannot be empty."));

$user = $DB->SELECT_ONE("users", "*", "WHERE username = '$username' OR email = '$username'");
if (empty($user)) die(toast("error", "Invalid credentials"));

if (!password_verify($password, $user['password'])) die(toast("error", message: "Password is invalid"));

$jwt = new JWT('this-is-secure-secret-key');
$user_token = $jwt->createToken([
    'user_id' => $user['user_id'],
    'username' => $user['username'],
    'email' => $user['email']
]);

if(isset($_POST['remember'])){
    $expiry = strtotime('+1 month');
}else{
    $expiry = 0;
}

if (setcookie("_xsrf-token", $user_token, $expiry, "/")){
    redirect("/dashboard");
}else{
    die(toast("error", "Failed to set cookie"));
}