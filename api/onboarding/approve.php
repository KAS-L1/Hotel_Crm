<?php
require("../../app/init.php");

if(isset($_POST['application_id'])){

    $application_id = $_POST['application_id'];
    $remarks = $_POST['remarks'];
 
    $data = [
        "status" => "Approved",
        "remarks" => $remarks,
        "updated_at" => date('Y-m-d H:i:s')
    ];

    $update_application = $DB->UPDATE(
        "vendors_application",
        $data,
        ["vendor_id" => $application_id]
    );
    if (!$update_application == "success") die(toast("error", "Failed to approve application"));

    $update_user = $DB->UPDATE(
        "users",
        ["status" => "Active"],
        ["user_id" => $application_id]
    );
    if (!$update_user == "success") die(toast("error", "Failed to update user"));

    toast("success", "Application approved successfully");
    die(refresh(2000));

}

?>

