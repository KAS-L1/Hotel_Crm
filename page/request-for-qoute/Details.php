<?php

$rfqId = $_GET['id'];

// Get RFQ Details
$rfq = $DB->SELECT_JOIN(
    ['rfq_requests', 'users'],
    't1.*, t2.company AS buyer_company',
    [
        [['t1.created_by', 't2.user_id']]
    ],
    ['INNER JOIN'],
    ['t1.rfq_id' => $rfqId],
    'LIMIT 1'
)[0] ?? null;

if (!$rfq) {
    redirect('/request-for-qoute', 'error', 'RFQ not found');
}

// Get All Responses for this RFQ
$responses = $DB->SELECT_JOIN(
    ['rfq_responses', 'users'],
    't1.*, t2.company AS vendor_company',
    [
        [['t1.vendor_id', 't2.user_id']]
    ],
    ['INNER JOIN'],
    ['t1.rfq_id' => $rfqId],
    'ORDER BY t1.unit_price ASC'
);

?>

<div class="page-content">
    <?php
    breadcrumb([
        ['label' => 'Home', 'url' => '/dashboard'],
        ['label' => 'RFQ Management', 'url' => '/request-for-qoute'],
        ['label' => 'Details', 'url' => '/request-for-qoute/details']
    ]);
    ?>

    <div class="pt-5">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
            <!-- RFQ Details -->
            <div class="panel">
                <div class="text-xl font-bold mb-4">RFQ Information - <?= $rfqId ?></div>

                <div class="space-y-4">
                    <div>
                        <label class="font-semibold">Product Name:</label>
                        <p><?= $rfq['product_name'] ?></p>
                    </div>

                    <div>
                        <label class="font-semibold">Category:</label>
                        <p><?= $rfq['category_id'] ?></p>
                    </div>

                    <div>
                        <label class="font-semibold">Quantity:</label>
                        <p><?= $rfq['quantity'] ?></p>
                    </div>

                    <div>
                        <label class="font-semibold">Delivery Date:</label>
                        <p><?= date('M d, Y H:i', strtotime($rfq['delivery_date'])) ?></p>
                    </div>

                    <div>
                        <label class="font-semibold">Status:</label>
                        <p><?= badge($rfq['status']) ?></p>
                    </div>

                    <div>
                        <label class="font-semibold">Preferred Terms:</label>
                        <p><?= nl2br($rfq['preferred_terms']) ?></p>
                    </div>

                    <div>
                        <label class="font-semibold">Detailed Requirements:</label>
                        <p><?= nl2br($rfq['detailed_req']) ?></p>
                    </div>
                </div>
            </div>

            <!-- Vendor Responses -->
            <div class="panel">
                <div class="text-xl font-bold mb-4">Vendor Responses</div>

                <div class="space-y-4">
                    <?php if (empty($responses)): ?>
                        <div class="alert alert-info">No responses yet</div>
                    <?php else: ?>
                        <?php foreach ($responses as $response): ?>
                            <div class="border rounded p-4 <?= $response['status'] === 'Accepted' ? 'bg-success-light border-success' : '' ?>">
                                <div class="flex justify-between items-start mb-2">
                                    <div class="font-semibold"><?= $response['vendor_company'] ?></div>
                                    <div><?= badge($response['status']) ?></div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label>Unit Price:</label>
                                        <p><?= PESO($response['unit_price']) ?></p>
                                    </div>

                                    <div>
                                        <label>Available Qty:</label>
                                        <p><?= $response['available_qty'] ?></p>
                                    </div>

                                    <div>
                                        <label>Delivery Time:</label>
                                        <p><?= $response['delivery_lead_time'] ?></p>
                                    </div>

                                    <div>
                                        <label>MOQ:</label>
                                        <p><?= $response['moq'] ?? 'N/A' ?></p>
                                    </div>
                                </div>

                                <?php if ($response['vendor_terms']): ?>
                                    <div class="mt-2">
                                        <label class="font-semibold">Vendor Terms:</label>
                                        <p><?= nl2br($response['vendor_terms']) ?></p>
                                    </div>
                                <?php endif; ?>
                                <!-- <input type="hidden" name="action" value="accept"> -->
                                <?php if ($rfq['status'] === 'Open' && ($response['status'] === 'Pending' || $response['status'] === 'Submitted')): ?>
                                    <div class="flex gap-2 mt-4">
                                        <button class="btn btn-success btn-sm btnAcceptResponse" data-response-id="<?= $response['response_id'] ?>">
                                            Accept
                                        </button>
                                        <button class="btn btn-danger btn-sm btnRejectResponse" data-response-id="<?= $response['response_id'] ?>">
                                            Reject
                                        </button>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

</div>

<div id="responseMessage"></div>

<script>
    $(document).ready(function() {
        $('.btnAcceptResponse').click(function() {
            const responseId = $(this).data('response-id');
            updateResponseStatus(responseId, 'Accepted');
        });

        $('.btnRejectResponse').click(function() {
            const responseId = $(this).data('response-id');
            updateResponseStatus(responseId, 'Rejected');
        });

        function updateResponseStatus(responseId, status) {
            if (confirm(`Are you sure you want to ${status.toLowerCase()} this response?`)) {
                $.post('../api/rfq/update_response_status.php', {
                    response_id: responseId,
                    status: status,
                }, function(res) {
                    $('#responseMessage').html(res);
                    if (res.includes('success')) {
                        setTimeout(() => window.location.reload(), 1500);
                    }
                }).fail(function() {
                    $('#responseMessage').html('<div class="alert alert-danger">Failed to update status</div>');
                });
            }
        }
    });
</script>