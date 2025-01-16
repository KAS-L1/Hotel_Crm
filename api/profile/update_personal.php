<?php require("../../app/init.php"); ?>
<?php require("../auth/auth.php") ?>

<?php

/**
 * UPDATE USER PERSONAL INFORMATION
 **/

csrfProtect('verify');

if (!isset($_POST['first_name']) or !isset($_POST['last_name']) or !isset($_POST['username']) or !isset($_POST['email']) or!isset($_POST['address']) or !isset($_POST['company']) or !isset($_POST['contact'])) die(toast('error', 'Invalid server request'));

$data = array(
    "first_name" => $DB->ESCAPE(VALID_STRING($_POST['first_name'])),
    "last_name" => $DB->ESCAPE(VALID_STRING($_POST['last_name'])),
    "username" => $DB->ESCAPE(VALID_STRING($_POST['username'])),
    "email" => $DB->ESCAPE(VALID_MAIL($_POST['email'])),
    "address" => $DB->ESCAPE(VALID_STRING($_POST['address'])),
    "company" => $DB->ESCAPE(VALID_STRING($_POST['company'])),
    "contact" => $DB->ESCAPE(VALID_NUMBER($_POST['contact']))
);

$where = array("user_id" => AUTH_USER_ID);
$update_user = $DB->UPDATE("users", $data, $where);
if (!$update_user['success']) die(toast('error', 'Failed to update Personal Information'));

toast('success', 'Personal information successfully updated');
die(refresh(2000));

?>
