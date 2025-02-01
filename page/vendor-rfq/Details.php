<?php
$groupId = $_GET['group_id'] ?? '';

if (empty($groupId)) {
    die(toast('error', 'RFQ Group ID is required'));
}

// Fetch all RFQs in the group
$rfqs = $DB->SELECT_WHERE('rfq_requests', '*', [
    'rfq_group_id' => $groupId,
    'status' => 'Open'
], 'ORDER BY rfq_id ASC');

if (empty($rfqs)) {
    die(toast('error', 'RFQs not found or closed'));
}

// Get existing responses
$existingResponses = [];
$responses = $DB->SELECT_WHERE('rfq_responses', '*', [
    'vendor_id' => AUTH_USER_ID
]);

foreach ($responses as $response) {
    $existingResponses[$response['rfq_id']] = $response;
}
?>

<div class="page-content">
    <?php
    breadcrumb([
        ['label' => 'Home', 'url' => '/dashboard'],
        ['label' => 'RFQ Management', 'url' => '/vendor-rfq'],
        ['label' => 'Bulk Response', 'url' => '/vendor-rfq/bulk-response'],
    ]);
    ?>

    <div class="pt-5">
        <div class="panel">
            <div class="mb-5">
                <h5 class="text-lg font-semibold dark:text-white-light">Bulk RFQ Response</h5>
                <p class="text-gray-500 text-sm mt-1">Respond to multiple RFQs at once</p>
            </div>

            <form id="bulkResponseForm" class="space-y-6">
                <?= csrfProtect('generate'); ?>

                <?php foreach ($rfqs as $index => $rfq): ?>
                    <?php
                    $existingResponse = $existingResponses[$rfq['rfq_id']] ?? null;
                    $unit_price = $existingResponse['unit_price'] ?? '';
                    $available_qty = $existingResponse['available_qty'] ?? '';
                    $delivery_lead_time = $existingResponse['delivery_lead_time'] ?? '';
                    $moq = $existingResponse['moq'] ?? '';
                    $vendor_terms = $existingResponse['vendor_terms'] ?? '';
                    ?>

                    <div class="border-b pb-6 last:border-0">
                        <div class="flex items-center justify-between mb-4">
                            <h6 class="text-lg font-semibold">Product <?= $index + 1 ?>: <?= htmlspecialchars($rfq['product_name']) ?></h6>
                            <span class="text-sm text-gray-500"><?= $rfq['rfq_id'] ?></span>
                        </div>

                        <!-- Product Details -->
                        <div class="grid grid-cols-2 gap-4 mb-4 text-sm">
                            <div>
                                <span class="text-gray-500">Quantity Required:</span>
                                <span class="font-medium ml-2"><?= number_format($rfq['quantity']) ?></span>
                            </div>
                            <div>
                                <span class="text-gray-500">Delivery Date:</span>
                                <span class="font-medium ml-2"><?= date('M d, Y', strtotime($rfq['delivery_date'])) ?></span>
                            </div>
                        </div>

                        <!-- Response Form Fields -->
                        <input type="hidden" name="responses[<?= $index ?>][rfq_id]" value="<?= $rfq['rfq_id'] ?>">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-group">
                                <label class="form-label">Unit Price (₱)</label>
                                <input type="number"
                                    name="responses[<?= $index ?>][unit_price]"
                                    value="<?= $unit_price ?>"
                                    class="form-input"
                                    required>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Available Quantity</label>
                                <input type="number"
                                    name="responses[<?= $index ?>][available_qty]"
                                    value="<?= $available_qty ?>"
                                    class="form-input"
                                    required>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Delivery Lead Time</label>
                                <input type="text"
                                    name="responses[<?= $index ?>][delivery_lead_time]"
                                    value="<?= $delivery_lead_time ?>"
                                    placeholder="e.g., 14 days"
                                    class="form-input"
                                    required>
                            </div>

                            <div class="form-group">
                                <label class="form-label">MOQ</label>
                                <input type="number"
                                    name="responses[<?= $index ?>][moq]"
                                    value="<?= $moq ?>"
                                    class="form-input"
                                    required>
                            </div>

                            <div class="form-group md:col-span-2">
                                <label class="form-label">Terms</label>
                                <textarea
                                    name="responses[<?= $index ?>][vendor_terms]"
                                    rows="3"
                                    class="form-textarea"><?= $vendor_terms ?></textarea>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

                <div class="flex justify-end mt-8">
                    <button type="submit" id="btnSubmit" class="btn btn-primary">
                        Submit All Responses
                    </button>
                </div>
            </form>

            <div id="responseMessage"></div>
        </div>
    </div>
</div>

<style>
    .form-group {
        @apply space-y-2;
    }

    .form-label {
        @apply block text-sm font-medium text-gray-700 dark:text-gray-300;
    }

    .form-input,
    .form-textarea {
        @apply block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 shadow-sm focus:border-primary focus:ring-primary dark:border-gray-700 dark:bg-gray-900;
    }
</style>

<script>
    $('#bulkResponseForm').submit(function(e) {
        e.preventDefault();
        if (!confirm('Are you sure you want to submit all responses?')) return;

        btnLoading('#btnSubmit');
        $.post('../api/rfq/response.php', $(this).serialize(), function(res) {
            $('#responseMessage').html(res);
            btnLoadingReset('#btnSubmit');
        });
    });
</script>