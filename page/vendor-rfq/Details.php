<?php
$rfqId = $_GET['id'] ?? '';

if (empty($rfqId)) die(toast('error', 'RFQ ID is required'));


// Fetch RFQ details and vendor response
$result = $DB->SELECT_JOIN(
    ['rfq_requests', 'rfq_responses'],
    't1.*, t2.unit_price, t2.available_qty, t2.delivery_lead_time, t2.moq, t2.vendor_terms, t2.status as response_status',
    [
        [['t1.rfq_id', 't2.rfq_id'], ['t2.vendor_id', AUTH_USER_ID]]
    ],
    ['LEFT JOIN'],
    ['t1.rfq_id' => $rfqId],
    'LIMIT 1'
);

if (empty($result)) die(toast('error', 'RFQ not found'));


$rfq = $result[0];
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
        <div class="mb-5 grid grid-cols-1 gap-5 lg:grid-cols-3 xl:grid-cols-4">
            <!-- RFQ Details Card -->
            <div class="panel">
                <div class="mb-5">
                    <div class="flex items-center justify-between">
                        <h5 class="text-lg font-semibold dark:text-white-light">RFQ Details</h5>
                        <span class="badge bg-primary text-white"><?= $rfq['rfq_id'] ?></span>
                    </div>

                    <div class="mt-5 space-y-5">
                        <!-- Product Name -->
                        <div class="flex flex-col">
                            <h6 class="text-xl font-semibold text-primary mb-2"><?= htmlspecialchars($rfq['product_name']) ?></h6>
                        </div>

                        <!-- Quantity -->
                        <div class="flex items-center space-x-3">
                            <div class="bg-primary/10 p-2 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Quantity</p>
                                <p class="font-semibold"><?= number_format($rfq['quantity']) ?></p>
                            </div>
                        </div>

                        <!-- Delivery Date -->
                        <div class="flex items-center space-x-3">
                            <div class="bg-success/10 p-2 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-success" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Delivery Date</p>
                                <p class="font-semibold"><?= date('M d, Y', strtotime($rfq['delivery_date'])) ?></p>
                            </div>
                        </div>

                        <!-- Requirements -->
                        <div class="space-y-2">
                            <div class="flex items-center space-x-3">
                                <div class="bg-warning/10 p-2 rounded-lg">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-warning" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Requirements</p>
                            </div>
                            <div class="requirements-content ml-11">
                                <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-3">
                                    <p class="whitespace-pre-wrap text-sm"><?= nl2br(htmlspecialchars($rfq['detailed_req'])) ?></p>
                                </div>
                            </div>
                        </div>

                        <!-- Terms -->
                        <div class="space-y-2">
                            <div class="flex items-center space-x-3">
                                <div class="bg-info/10 p-2 rounded-lg">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-info" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Terms</p>
                            </div>
                            <div class="ml-11">
                                <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-3">
                                    <p class="whitespace-pre-wrap text-sm"><?= nl2br(htmlspecialchars($rfq['preferred_terms'])) ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Response Form -->
            <div class="panel lg:col-span-2 xl:col-span-3">
                <form id="vendorResponseForm" class="space-y-5">
                    <?= csrfProtect('generate'); ?>
                    <input type="hidden" name="rfq_id" value="<?= $rfq['rfq_id'] ?>">
                    <div class="mb-5">
                        <h5 class="text-lg font-semibold dark:text-white-light">Response Form</h5>
                        <p class="text-gray-500 text-sm mt-1">Please provide your quotation details below</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <!-- Unit Price -->
                        <div class="form-group">
                            <label for="unit_price" class="form-label">Unit Price (₱)</label>
                            <?= input("number", "unit_price", $rfq['unit_price'] ?? '', null, null, null, 'required') ?>
                        </div>

                        <!-- Available Quantity -->
                        <div class="form-group">
                            <label for="available_qty" class="form-label">Available Quantity</label>
                            <?= input("number", "available_qty", $rfq['available_qty'] ?? '', null, "step = '0.0.1'", null, 'required') ?>
                        </div>

                        <!-- Delivery Lead Time -->
                        <div class="form-group">
                            <label for="delivery_lead_time" class="form-label">Delivery Lead Time</label>
                            <?= input("text", "delivery_lead_time", $rfq['delivery_lead_time'] ?? '', "e.g., 14 days", null, null, 'required') ?>
                        </div>

                        <!-- MOQ -->
                        <div class="form-group">
                            <label for="moq" class="form-label">MOQ (Minimum Order Quantity)</label>
                            <?= input("number", "moq", $product['moq'] ?? '', null, null, null, 'required') ?>
                        </div>

                        <!-- Vendor Terms -->
                        <div class="form-group md:col-span-2">
                            <label for="vendor_terms" class="form-label">Your Terms</label>
                            <?= textarea("vendor_terms", $rfq['vendor_terms'] ?? '', null, null, null, "4", null) ?>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end mt-8">
                        <button type="submit" id="btnResponse" class="btn btn-primary">
                            <?= $rfq ? 'Update Response' : 'Submit Response' ?>
                        </button>
                    </div>
                </form>
                <div id="responseVendorResponse"></div>
                <script>
                    $('#vendorResponseForm').submit(function(e) {
                        e.preventDefault();
                        btnLoading('#btnResponse');
                        $.post('../api/rfq/response.php', $('#vendorResponseForm').serialize(), function(res) {
                            $('#responseVendorResponse').html(res);
                            btnLoadingReset('#btnResponse');
                        })
                    });
                </script>
            </div>
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

    .badge {
        @apply px-2.5 py-0.5 text-xs font-medium rounded-full;
    }
</style>