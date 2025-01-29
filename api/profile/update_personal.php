<?php require("../../app/init.php"); ?>
<?php require("../auth/auth.php") ?>

<?php

// Ensure CSRF protection
csrfProtect('verify');

// Check if all required POST data exists
$requiredFields = ['first_name', 'last_name', 'username', 'email', 'address', 'company', 'contact'];
foreach ($requiredFields as $field) {
    if (!isset($_POST[$field])) {
        die(toast('error', 'Invalid server request'));
    }
}

// Sanitize and validate input data
$data = [
    "first_name" => $DB->ESCAPE(VALID_STRING($_POST['first_name'])),
    "last_name"  => $DB->ESCAPE(VALID_STRING($_POST['last_name'])),
    "username"   => $DB->ESCAPE(VALID_STRING($_POST['username'])),
    "email"      => $DB->ESCAPE(VALID_MAIL($_POST['email'])),
    "address"    => $DB->ESCAPE(VALID_STRING($_POST['address'])),
    "company"    => $DB->ESCAPE(VALID_STRING($_POST['company'])),
    "contact"    => $DB->ESCAPE(VALID_NUMBER($_POST['contact']))
];

$where = ["user_id" => AUTH_USER_ID];

$update_user = $DB->UPDATE("users", $data, $where);

if (!$update_user === "success") die(toast('error', 'Failed to update Personal Information'));

$notification_data = [
    "user_id" => AUTH_USER_ID,
    "message" => "You personal information has been updated",
    "action" => "PersonalInformation",
    "created_at" => DATE_TIME
];

$DB->INSERT("notifications", $notification_data);

toast('success', 'Personal information successfully updated');
die(refresh(2000));


