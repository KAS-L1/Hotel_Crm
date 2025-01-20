<?php
require("../../app/init.php");

csrfProtect('verify'); // Add CSRF protection

$application_id = $_POST['application_id'];

predie($application_id);

if (empty($application_id)) die(toast("error", "Application ID is required"));

$data = [
    "status" => "Approved",
    "updated_at" => DATE_TIME
];

$approve_data = $DB->UPDATE('vendors_application', $data, ["id" => $application_id]); 
if (!$approve_data == 'success') die(toast("error", "Failed to approve application"));

toast("success", "Application successfully approved");
die(refresh(2000)); 
