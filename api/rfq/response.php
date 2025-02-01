<?php
require("../../app/init.php");
require("../auth/auth.php");

csrfProtect('verify');

// Validate input
if (!isset($_POST['responses']) || !is_array($_POST['responses'])) {
    die(toast('error', 'No responses provided'));
}

$success_count = 0;
$groupId = null;

foreach ($_POST['responses'] as $response) {
    // Validate required fields
    if (!isset(
        $response['rfq_id'],
        $response['unit_price'],
        $response['available_qty'],
        $response['delivery_lead_time'],
        $response['moq']
    )) {
        continue;
    }

    $rfqId = $response['rfq_id'];

    // Get RFQ details
    $rfq = $DB->SELECT_ONE_WHERE('rfq_requests', '*', [
        'rfq_id' => $rfqId,
        'status' => 'Open'
    ]);

    if (!$rfq) continue;

    if (!$groupId) {
        $groupId = $rfq['rfq_group_id'];
    }

    // Check existing response
    $existingResponse = $DB->SELECT_ONE_WHERE('rfq_responses', '*', [
        'rfq_id' => $rfqId,
        'vendor_id' => AUTH_USER_ID
    ]);

    // Prepare response data
    $responseData = [
        'rfq_id' => $rfqId,
        'vendor_id' => AUTH_USER_ID,
        'unit_price' => floatval($response['unit_price']),
        'available_qty' => intval($response['available_qty']),
        'delivery_lead_time' => $DB->ESCAPE($response['delivery_lead_time']),
        'moq' => intval($response['moq']),
        'vendor_terms' => $DB->ESCAPE($response['vendor_terms'] ?? ''),
        'status' => 'Submitted',
        'updated_at' => date('Y-m-d H:i:s')
    ];

    try {
        if ($existingResponse) {
            // Update existing response
            $result = $DB->UPDATE('rfq_responses', $responseData, [
                'response_id' => $existingResponse['response_id']
            ]);
        } else {
            // Create new response
            $responseData['response_id'] = GENERATE_ID('RES-', 6);
            $responseData['created_at'] = date('Y-m-d H:i:s');
            $result = $DB->INSERT('rfq_responses', $responseData);
        }

        if ($result['success']) {
            $success_count++;

            // Notify buyer
            $DB->INSERT('notifications', [
                'user_id' => $rfq['created_by'],
                'message' => $existingResponse
                    ? "Updated response for RFQ {$rfqId}"
                    : "New response for RFQ {$rfqId}",
                'action' => "/request-for-qoute/details?id={$rfqId}",
                'status' => 'Unread',
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }
    } catch (Exception $e) {
        continue;
    }
}

if ($success_count > 0) {
    // Notify vendor of bulk response success
    $DB->INSERT('notifications', [
        'user_id' => AUTH_USER_ID,
        'message' => "Successfully submitted responses for {$success_count} products in bulk RFQ",
        'action' => "/vendor-rfq/details?group_id={$groupId}",
        'status' => 'Unread',
        'created_at' => date('Y-m-d H:i:s')
    ]);

    toast('success', "Successfully submitted {$success_count} responses");
    die(redirect('/vendor-rfq', 2000));
} else {
    die(toast('error', 'Failed to submit responses'));
}
