<?php
require("../../app/init.php");

if(isset($_POST['application_id'])){

    $application_id = $_POST['application_id'];
    $remarks = $_POST['remarks'];
 
    $data = [
        "status" => "Declined",
        "remarks" => $remarks,
        "updated_at" => date('Y-m-d H:i:s')
    ];

    $update_application = $DB->UPDATE(
        "vendors_application",
        $data,
        ["vendor_id" => $application_id]
    );
    if (!$update_application == "success") die(toast("error", "Failed to decline application"));

    $update_user = $DB->UPDATE(
        "users",
        ["status" => "pending"],
        ["user_id" => $application_id]
    );
    if (!$update_user == "success") die(toast("error", "Failed to update user"));


    $notification_data = [
        "user_id" => $application_id,
        "message" => "Sorry application has been declined.",
        "action" => "Application",
        "created_at" => DATE_TIME
    ];

    $DB->INSERT("notifications", $notification_data);
    toast("success", "Application declined successfully");
    die(refresh(2000));

}

?>


