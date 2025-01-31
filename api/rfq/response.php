<?php
require("../../app/init.php");
require("../auth/auth.php");

// Verify CSRF and request method
csrfProtect('verify');

// Validate input
$rfqId = $_POST['rfq_id'] ?? '';
if (empty($rfqId)) die(toast('error', 'RFQ ID is required'));

$requiredFields = [
    'unit_price' => 'Unit Price',
    'available_qty' => 'Available Quantity',
    'delivery_lead_time' => 'Delivery Lead Time',
    'moq' => 'MOQ'
];

foreach ($requiredFields as $field => $label) {
    if (empty($_POST[$field])) die(toast('error', "$label is required"));
}

// Get RFQ status
$rfq = $DB->SELECT_ONE_WHERE('rfq_requests', '*', [
    'rfq_id' => $rfqId,
    'status' => 'Open'
]);
if (!$rfq) die(toast('error', 'RFQ not found or closed'));

// Check existing response
$existingResponse = $DB->SELECT_ONE_WHERE('rfq_responses', '*', [
    'rfq_id' => $rfqId,
    'vendor_id' => AUTH_USER_ID
]);

// Prepare data
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

try {
    if ($existingResponse) {
        // Update existing
        $result = $DB->UPDATE('rfq_responses', $responseData, [
            'response_id' => $existingResponse['response_id']
        ]);
        $message = 'Response updated successfully';
    } else {
        // Create new
        $responseData['response_id'] = GENERATE_ID('RES-', 6);
        $responseData['created_at'] = date('Y-m-d H:i:s');
        $result = $DB->INSERT('rfq_responses', $responseData);
        $message = 'Response submitted successfully';
    }

    if (!$result['success']) throw new Exception($result['message']);

    // Send notifications
    $notificationData = [
        'buyer' => [
            'user_id' => $rfq['created_by'],
            'message' => $existingResponse
                ? "Updated response for RFQ {$rfqId}"
                : "New response for RFQ {$rfqId}"
        ],
        'vendor' => [
            'user_id' => AUTH_USER_ID,
            'message' => $existingResponse
                ? "Your response for RFQ {$rfqId} was updated"
                : "Response for RFQ {$rfqId} submitted"
        ]
    ];

    foreach ($notificationData as $data) {
        $DB->INSERT('notifications', [
            'user_id' => $data['user_id'],
            'message' => $data['message'],
            'action' => "/request-for-qoute/details?id={$rfqId}",
            'status' => 'Unread',
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    toast('success', $message);
    redirect('/vendor-rfq', 2000);
    exit;
} catch (Exception $e) {
    die(toast('error', 'Operation failed: ' . $e->getMessage()));
}
