<?php

require("../../app/init.php");
require("../auth/auth.php");

if (isset($_POST['response_id']) && isset($_POST['status'])) {
    $response_id = $DB->ESCAPE($_POST['response_id']);
    $status = $DB->ESCAPE($_POST['status']);

    if (!in_array($status, ['Accepted', 'Rejected'])) {
        die(toast("error", "Invalid status specified"));
    }

    // Get RFQ response details
    $response = $DB->SELECT_ONE_WHERE("rfq_responses", "rfq_id, vendor_id", ["response_id" => $response_id]);
    if (!$response) die(toast("error", "RFQ response not found"));

    // Get RFQ details
    $rfq = $DB->SELECT_ONE_WHERE("rfq_requests", "product_name, created_by", ["rfq_id" => $response['rfq_id']]);
    if (!$rfq) die(toast("error", "RFQ not found"));

    // Update the response status
    $update_response = $DB->UPDATE("rfq_responses", ["status" => $status, "updated_at" => date('Y-m-d H:i:s')], ["response_id" => $response_id]);
    if (!$update_response['success']) die(toast("error", "Failed to update RFQ response"));

    if ($status === 'Accepted') {
        // Update RFQ status and reject other responses
        $update_rfq = $DB->UPDATE("rfq_requests", ["status" => "Awarded", "updated_at" => date('Y-m-d H:i:s')], ["rfq_id" => $response['rfq_id']]);
        if (!$update_rfq['success']) die(toast("error", "Failed to update RFQ status"));

        $reject_others = $DB->UPDATE("rfq_responses", ["status" => "Rejected", "updated_at" => date('Y-m-d H:i:s')], ["rfq_id" => $response['rfq_id'], "response_id !=" => $response_id]);
        if (!$reject_others['success']) die(toast("error", "Failed to reject other responses"));

        // Notify other vendors
        $other_vendors = $DB->SELECT_WHERE("rfq_responses", "vendor_id", ["rfq_id" => $response['rfq_id'], "response_id !=" => $response_id]);
        foreach ($other_vendors as $vendor) {
            $DB->INSERT("notifications", [
                "user_id" => $vendor['vendor_id'],
                "message" => "Your response to RFQ #{$response['rfq_id']} for {$rfq['product_name']} was not selected.",
                "action" => "RFQResponse",
                "status" => "Unread",
                "created_at" => DATE_TIME
            ]);
        }
    }

    // Notify the action user and vendor
    $vendor_message = ($status === 'Accepted') ? "Your response to RFQ #{$response['rfq_id']} for {$rfq['product_name']} has been accepted." : "Your response to RFQ #{$response['rfq_id']} for {$rfq['product_name']} has been rejected.";
    $DB->INSERT("notifications", [
        "user_id" => $response['vendor_id'],
        "message" => $vendor_message,
        "action" => "RFQResponse",
        "status" => "Unread",
        "created_at" => DATE_TIME
    ]);

    $action_user_message = ($status === 'Accepted') ? "You have accepted the response from vendor #{$response['vendor_id']} for RFQ #{$response['rfq_id']} ({$rfq['product_name']})." : "You have rejected the response from vendor #{$response['vendor_id']} for RFQ #{$response['rfq_id']} ({$rfq['product_name']}).";
    $DB->INSERT("notifications", [
        "user_id" => AUTH_USER_ID,
        "message" => $action_user_message,
        "action" => "RFQResponse",
        "status" => "Unread",
        "created_at" => DATE_TIME
    ]);

    toast("success", "RFQ response " . strtolower($status) . " successfully");
    die(refresh(2000));
}

toast("error", "Invalid request parameters");
die(redirect('/request-for-qoute'));
