<?php
require("../../app/init.php");
require("../auth/auth.php");

csrfProtect('verify');

// Check if products array exists
if (!isset($_POST['products']) || !is_array($_POST['products'])) {
    die(toast('error', 'No products provided'));
}

// Check common fields
if (!isset($_POST['preferred_terms']) || empty($_POST['preferred_terms'])) {
    die(toast('error', 'Preferred terms is required'));
}

if (!isset($_POST['delivery_date']) || empty($_POST['delivery_date'])) {
    die(toast('error', 'Delivery date is required'));
}

// Generate a common RFQ ID for all products
$rfq_group_id = GENERATE_ID('RFQ-', 6);
$success_count = 0;

// Process each product
foreach ($_POST['products'] as $product) {
    // Validate required fields for each product
    if (
        empty($product['product_name']) || empty($product['category_id']) ||
        empty($product['quantity'])
    ) {
        continue; // Skip invalid products
    }

    // Prepare RFQ data for each product
    $rfqData = [
        'rfq_id' => $rfq_group_id . '-' . ($success_count + 1), // Unique ID for each product
        'rfq_group_id' => $rfq_group_id,
        'product_name' => $DB->ESCAPE(VALID_STRING($product['product_name'])),
        'category_id' => $DB->ESCAPE(VALID_NUMBER($product['category_id'])),
        'detailed_req' => $DB->ESCAPE(VALID_STRING($product['detailed_req'] ?? '')),
        'quantity' => $DB->ESCAPE(VALID_NUMBER($product['quantity'])),
        'preferred_terms' => $DB->ESCAPE(VALID_STRING($_POST['preferred_terms'])),
        'delivery_date' => $DB->ESCAPE($_POST['delivery_date']),
        'status' => 'Open',
        'created_by' => AUTH_USER_ID
    ];

    // Save RFQ
    $result = $DB->INSERT('rfq_requests', $rfqData);

    if ($result["success"]) {
        $success_count++;
    }
}

// If at least one product was successfully added
if ($success_count > 0) {
    // Notify selected vendors if any
    if (isset($_POST['vendors']) && is_array($_POST['vendors'])) {
        foreach ($_POST['vendors'] as $vendorId) {
            $vendorId = intval($vendorId);
            if ($vendorId > 0) {
                $DB->INSERT('notifications', [
                    'user_id' => $vendorId,
                    'message' => "New Bulk RFQ Created: {$success_count} products",
                    'action' => "/vendor-rfq/details?group_id={$rfq_group_id}",
                    'status' => 'Unread'
                ]);
            }
        }
    }

    // Create notification for the creator
    $notification_data = [
        "user_id" => AUTH_USER_ID,
        "message" => "Bulk RFQ has been created successfully with {$success_count} products",
        "action" => "RFQCreation",
        "created_at" => DATE_TIME
    ];

    $DB->INSERT("notifications", $notification_data);

    toast('success', "RFQ created successfully with {$success_count} products");
    die(redirect('/request-for-qoute', 2000));
} else {
    die(toast('error', 'Failed to create RFQ'));
    redirect('/request-for-qoute/create', 2000);
}
