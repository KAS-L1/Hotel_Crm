<?php
// vendor_rfq_details.php

$rfqId = $_GET['id'];

// Get RFQ Details
$rfq = $DB->SELECT_ONE_WHERE('rfq_requests', '*', ['rfq_id' => $rfqId]);

// Check if vendor already responded
$existingResponse = $DB->SELECT_ONE_WHERE('rfq_responses', '*', [
    'rfq_id' => $rfqId,
    'vendor_id' => AUTH_USER_ID
]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $responseData = [
        'response_id' => GENERATE_ID('RES-', 6),
        'rfq_id' => $rfqId,
        'vendor_id' => AUTH_USER_ID,
        'unit_price' => floatval($_POST['unit_price']),
        'available_qty' => intval($_POST['available_qty']),
        'delivery_lead_time' => $DB->ESCAPE($_POST['delivery_lead_time']),
        'moq' => intval($_POST['moq'] ?? 0),
        'vendor_terms' => $DB->ESCAPE($_POST['vendor_terms']),
        'status' => 'Submitted'
    ];

    if ($existingResponse) {
        // Update existing response
        $result = $DB->UPDATE('rfq_responses', $responseData, [
            'response_id' => $existingResponse['response_id']
        ]);
    } else {
        // Create new response
        $result = $DB->INSERT('rfq_responses', $responseData);
    }

    if ($result['success']) {
        // Mark notification as read
        $DB->UPDATE('notifications', ['status' => 'Read'], [
            'user_id' => AUTH_USER_ID,
            'action' => "/vendor_rfq_details?id={$rfqId}"
        ]);

        redirect('/vendor_rfq_list', 'success', 'Response submitted successfully');
    } else {
        redirect("/vendor_rfq_details?id={$rfqId}", 'error', 'Failed to submit response');
    }
}
?>

<div class="page-content">

    <?php
    breadcrumb([
        ['label' => 'Home', 'url' => '/dashboard'],
        ['label' => 'RFQ Management', 'url' => '/vendor-rfq'],
        ['label' => 'Details', 'url' => '/vendor-rfq/details'],
    ]);
    ?>

    <div class="pt-5">
        <div class="panel">
            <div class="text-xl font-bold mb-4">RFQ: <?= $rfq['product_name'] ?></div>

            <!-- RFQ Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div><strong>Quantity:</strong> <?= $rfq['quantity'] ?></div>
                <div><strong>Delivery Date:</strong> <?= date('M d, Y', strtotime($rfq['delivery_date'])) ?></div>
                <div class="md:col-span-2"><strong>Requirements:</strong> <?= nl2br($rfq['detailed_req']) ?></div>
                <div class="md:col-span-2"><strong>Terms:</strong> <?= nl2br($rfq['preferred_terms']) ?></div>
            </div>

            <!-- Response Form -->
            <form method="POST">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-group">
                        <label>Unit Price (₱)</label>
                        <input type="number" name="unit_price" step="0.01" required
                            value="<?= $existingResponse['unit_price'] ?? '' ?>"
                            class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Available Quantity</label>
                        <input type="number" name="available_qty" required
                            value="<?= $existingResponse['available_qty'] ?? '' ?>"
                            class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Delivery Lead Time</label>
                        <input type="text" name="delivery_lead_time" required
                            value="<?= $existingResponse['delivery_lead_time'] ?? '' ?>"
                            placeholder="e.g., 14 days" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>MOQ (Minimum Order Quantity)</label>
                        <input type="number" name="moq"
                            value="<?= $existingResponse['moq'] ?? '' ?>"
                            class="form-control">
                    </div>

                    <div class="md:col-span-2 form-group">
                        <label>Your Terms</label>
                        <textarea name="vendor_terms" rows="3" class="form-control"><?=
                        $existingResponse['vendor_terms'] ?? ''
                        ?></textarea>
                    </div>

                    <div class="md:col-span-2">
                        <button type="submit" class="btn btn-primary">
                            <?= $existingResponse ? 'Update Response' : 'Submit Response' ?>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>