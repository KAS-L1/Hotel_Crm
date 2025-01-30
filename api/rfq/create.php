<?php

// Get active vendors and categories
$vendors = $DB->SELECT_WHERE('users', '*', ['role' => 'Vendor', 'status' => 'Active']);
$categories = $DB->SELECT('product_categories');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Generate RFQ ID
    $rfqId = GENERATE_ID('RFQ-', 6);

    // Prepare RFQ data
    $rfqData = [
        'rfq_id' => $rfqId,
        'product_name' => $DB->ESCAPE($_POST['product_name']),
        'category_id' => intval($_POST['category_id']),
        'detailed_req' => $DB->ESCAPE($_POST['detailed_req']),
        'quantity' => intval($_POST['quantity']),
        'preferred_terms' => $DB->ESCAPE($_POST['preferred_terms']),
        'delivery_date' => $DB->ESCAPE($_POST['delivery_date']),
        'status' => 'Open',
        'created_by' => AUTH_USER_ID
    ];

    // Save RFQ
    $result = $DB->INSERT('rfq_requests', $rfqData);

    if ($result['success']) {
        // Notify selected vendors
        foreach ($_POST['vendors'] as $vendorId) {
            // Save notification
            $DB->INSERT('notifications', [
                'user_id' => $vendorId,
                'message' => "New RFQ: {$rfqData['product_name']}",
                'action' => "/vendor_rfq_details?id={$rfqId}"
            ]);
        }

        redirect('/request-for-qoute', 'success', 'RFQ created successfully');
    } else {
        redirect('/request-for-qoute/create', 'error', 'Failed to create RFQ');
    }
}
?>

<!-- Form UI from previous example remains the same -->