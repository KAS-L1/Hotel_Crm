<?php
require("../../app/init.php");
require("../auth/auth.php");

// Verify CSRF and check if request is POST
csrfProtect('verify');

// Get RFQ ID from hidden input
$rfqId = $_POST['rfq_id'] ?? '';
if (empty($rfqId)) {
    die(toast('error', 'RFQ ID is required'));
}

// Validate required fields
$requiredFields = [
    'unit_price' => 'Unit Price',
    'available_qty' => 'Available Quantity',
    'delivery_lead_time' => 'Delivery Lead Time',
    'moq' => 'Minimum Order Quantity'
];

foreach ($requiredFields as $field => $label) {
    if (empty($_POST[$field])) {
        die(toast('error', "{$label} is required"));
    }
}


    // Check if RFQ exists and is open
    $rfq = $DB->SELECT_ONE_WHERE('rfq_requests', '*', [
        'rfq_id' => $rfqId,
        'status' => 'Open'
    ]);

    if (!$rfq) {
        die(toast('error', 'RFQ not found or already closed'));
    }

    // Get existing response if any
    $existingResponse = $DB->SELECT_ONE_WHERE('rfq_responses', '*', [
        'rfq_id' => $rfqId,
        'vendor_id' => AUTH_USER_ID
    ]);

    // Prepare response data
    $responseData = [
        'rfq_id' => $rfqId,
        'vendor_id' => AUTH_USER_ID,
        'unit_price' => floatval($_POST['unit_price']),
        'available_qty' => intval($_POST['available_qty']),
        'delivery_lead_time' => $DB->ESCAPE($_POST['delivery_lead_time']),
        'moq' => intval($_POST['moq']),
        'vendor_terms' => $DB->ESCAPE($_POST['vendor_terms'] ?? ''),
        'status' => 'Submitted',
        'updated_at' => date('Y-m-d H:i:s')
    ];

    if ($existingResponse) {
        // Update existing response
        $result = $DB->UPDATE('rfq_responses', $responseData, [
            'response_id' => $existingResponse['response_id']
        ]);
        if ($result['success']) {
            toast('success', 'Response updated successfully');
            die(redirect('/vendor-rfq', 2000));
        } else {
            die(toast('error', 'Response failed to be updated successfully'));
        }
    } else {
        // Create new response
        $responseData['response_id'] = GENERATE_ID('RES-', 6);
        $responseData['created_at'] = date('Y-m-d H:i:s');

        $result = $DB->INSERT('rfq_responses', $responseData);
        if ($result['success']) {
            // Create notification
            $DB->INSERT('notifications', [
                'user_id' => AUTH_USER_ID, //created by
                'message' => "Vendor has submitted a response for RFQ {$rfqId}",
                'action' => "/request-for-qoute/details?id={$rfqId}",
                'status' => 'Unread',
                'created_at' => date('Y-m-d H:i:s')
            ]);

        $DB->UPDATE('notifications', ['status' => 'Read'], [
            'user_id' => AUTH_USER_ID,
            'action' => "/vendor-rfq/details?id={$rfqId}",
            'status' => 'Unread'
        ]);
            toast('success', 'Response submitted successfully');
            die(redirect('/vendor-rfq', 2000));
        } else {
            die(toast('error', 'Failed to submit response'));
        }
    }

   

