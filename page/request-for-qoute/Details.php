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

// Helper function for status badges
function getStatusBadgeClass($status)
{
    return match ($status) {
        'Open' => 'bg-blue-500',
        'Pending' => 'bg-yellow-500',
        'Submitted' => 'bg-orange-500',
        'Accepted' => 'bg-green-500',
        'Rejected' => 'bg-red-500',
        default => 'bg-gray-500'
    };
}
?>

<div class="max-w-7xl mx-auto p-4 space-y-6">
    <!-- Breadcrumb -->
    <nav class="flex items-center space-x-2 text-sm text-gray-500">
        <?php
        breadcrumb([
            ['label' => 'Home', 'url' => '/dashboard'],
            ['label' => 'RFQ Management', 'url' => '/request-for-qoute'],
            ['label' => 'Details', 'url' => '/request-for-qoute/details']
        ]);
        ?>
    </nav>

    <div class="grid lg:grid-cols-2 gap-6">
        <!-- RFQ Information Card -->
        <div class="panel rounded-lg shadow-sm border border-primary">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold">RFQ Information - <?= htmlspecialchars($rfqId) ?></h2>
                    <span
                        class="px-3 py-1 rounded-full text-sm font-medium text-white <?= getStatusBadgeClass($rfq['status']) ?>">
                        <?= badge($rfq['status']) ?>
                    </span>
                </div>

                <div class="space-y-6">
                    <!-- Product Details -->
                    <div class="grid gap-6">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Product Name</p>
                                <p class="text-base"><?= htmlspecialchars($rfq['product_name']) ?></p>
                            </div>
                        </div>

                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Category</p>
                                <p class="text-base"><?= htmlspecialchars($rfq['category_id']) ?></p>
                            </div>
                        </div>

                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Quantity</p>
                                <p class="text-base"><?= htmlspecialchars($rfq['quantity']) ?></p>
                            </div>
                        </div>

                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Delivery Date</p>
                                <p class="text-base"><?= date('M d, Y H:i', strtotime($rfq['delivery_date'])) ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Terms and Requirements -->
                    <div class="space-y-4 border-t pt-4 dark:border-gray-700">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Preferred Terms</h3>
                            <p class="text-base whitespace-pre-line">
                                <?= nl2br(htmlspecialchars($rfq['preferred_terms'])) ?>
                            </p>
                        </div>

                        <div>
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Detailed Requirements
                            </h3>
                            <p class="text-base whitespace-pre-line">
                                <?= nl2br(htmlspecialchars($rfq['detailed_req'])) ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Vendor Responses -->
        <div class="panel rounded-lg shadow-sm border border-primary">
            <div class="p-6">
                <h2 class="text-xl font-bold mb-6">Vendor Responses</h2>

                <?php if (empty($responses)): ?>
                    <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                        No responses yet
                    </div>
                <?php else: ?>
                    <div class="space-y-4">
                        <?php foreach ($responses as $response): ?>
                            <div class="border rounded p-4 <?= $response['status'] === 'Accepted' ? 'bg-success-light dark:bg-opacity-[0.08] border-success' : ($response['status'] === 'Rejected' ? 'bg-danger-light dark:bg-opacity-[0.08] border-danger' : '') ?>">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="font-medium"><?= htmlspecialchars($response['vendor_company']) ?></div>
                                    <span
                                        class="px-3 py-1 rounded-full text-sm font-medium text-white <?= getStatusBadgeClass($response['status']) ?>">
                                        <?= badge($response['status']) ?>
                                    </span>
                                </div>

                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Unit Price</p>
                                        <p class="font-medium"><?= PESO($response['unit_price']) ?></p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Available Qty</p>
                                        <p class="font-medium"><?= htmlspecialchars($response['available_qty']) ?></p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Delivery Time</p>
                                        <p class="font-medium"><?= htmlspecialchars($response['delivery_lead_time']) ?></p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">MOQ</p>
                                        <p class="font-medium"><?= htmlspecialchars($response['moq'] ?? 'N/A') ?></p>
                                    </div>
                                </div>

                                <?php if ($response['vendor_terms']): ?>
                                    <div class="border-t pt-4 dark:border-gray-700">
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Vendor Terms</p>
                                        <p class="whitespace-pre-line"><?= nl2br(htmlspecialchars($response['vendor_terms'])) ?></p>
                                    </div>
                                <?php endif; ?>

                                <?php if ($rfq['status'] === 'Open' && ($response['status'] === 'Pending' || $response['status'] === 'Submitted')): ?>
                                    <div class="flex gap-2 mt-4">
                                        <button class="btn btn-success btn-sm btnAcceptResponse"
                                            data-response-id="<?= $response['response_id'] ?>">
                                            Accept
                                        </button>
                                        <button class="btn btn-danger btn-sm btnRejectResponse"
                                            data-response-id="<?= $response['response_id'] ?>">
                                            Reject
                                        </button>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
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