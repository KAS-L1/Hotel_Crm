<?php require("../../app/init.php"); ?>
<?php require("../auth/auth.php"); ?>

<?php

/**
 * UPDATE USER PERSONAL PASSWORD
 **/


csrfProtect('verify');

$newPassword = $_POST['newPassword'];
$confirmPassword = $_POST['confirmPassword'];

if (!VALID_STRONG_PASS($newPassword)) {
    die(); 
}

if ($newPassword !== $confirmPassword) {
    toast("error", "Passwords do not match.");
    die();
}

$hashedPassword = HASH_PASSWORD($newPassword);

$data = array(
    "password" => $hashedPassword, 
);

$where = array("user_id" => AUTH_USER_ID);
$update_password = $DB->UPDATE("users", $data, $where);

if (!$update_password['success']) die(toast("error", "Failed to update password"));

toast("success", "Password updated successfully");
die(refresh(2000));  
