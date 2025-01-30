<?php
require("../../app/init.php");
require("../auth/auth.php");

csrfProtect('verify');

// Check required fields
$required = ['product_name', 'category_id', 'detailed_req', 'quantity', 'preferred_terms', 'delivery_date'];
foreach ($required as $field) {
    if (!isset($_POST[$field]) || empty($_POST[$field])) {
        die(toast('error', 'Missing required field: $field'));
    }
}
    // Prepare RFQ data
    $rfqData = [
        'rfq_id' => GENERATE_ID('RFQ-', 6),
        'product_name' => $DB->ESCAPE(VALID_STRING($_POST['product_name'])),
        'category_id' => $DB->ESCAPE(VALID_NUMBER($_POST['category_id'])),
        'detailed_req' => $DB->ESCAPE(VALID_STRING($_POST['detailed_req'])),
        'quantity' => $DB->ESCAPE(VALID_NUMBER($_POST['quantity'])),
        'preferred_terms' => $DB->ESCAPE(VALID_STRING($_POST['preferred_terms'])),
        'delivery_date' => $DB->ESCAPE($_POST['delivery_date']),
        'status' => 'Open',
        'created_by' => AUTH_USER_ID
    ];

    // Save RFQ
    $result = $DB->INSERT('rfq_requests', $rfqData);

    if ($result["success"]) {
        // Notify selected vendors if any
        if (isset($_POST['vendors']) && is_array($_POST['vendors'])) {
            foreach ($_POST['vendors'] as $vendorId) {
                $vendorId = intval($vendorId);
                if ($vendorId > 0) {
                    $DB->INSERT('notifications', [
                        'user_id' => $vendorId,
                        'message' => "New RFQ: {$rfqData['product_name']}",
                        'action' => "/vendor-rfq/details?id={$rfqData['rfq_id']}",
                        'status' => 'Unread'
                    ]);
                }
            }
        }

    $notification_data = [
        "user_id" => AUTH_USER_ID,
        "message" => "RFQ has been created successfully",
        "action" => "RFQCreation",
        "created_at" => DATE_TIME
    ];


    $DB->INSERT("notifications", $notification_data);
        
        toast('success', 'RFQ created successfully');
        die(redirect('/request-for-qoute', 2000));
    } else {
        die(toast('error', 'Failed to create RFQ'));
        redirect('/request-for-qoute/create', 2000);
    }
