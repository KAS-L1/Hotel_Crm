<?php
// create_rfq.php

// Get all active vendors
$vendors = $DB->SELECT_WHERE('users', '*', [
    'role' => 'Vendor',
    'status' => 'Active'
]);

// Get product categories
$categories = $DB->SELECT('product_categories');
?>

<div class="page-content">
    <?php
    breadcrumb([
        ['label' => 'Home', 'url' => '/dashboard'],
        ['label' => 'RFQ Management', 'url' => '/request-for-qoute'],
        ['label' => 'Create', 'url' => '/request-for-qoute/create'],
    ]);
    ?>

    <div class="pt-5">
        <div class="panel lg:col-span-2 xl:col-span-3">
            <div class="mb-5">
                <form id="formCreateRFQ">
                    <?= csrfProtect('generate'); ?>
                    <div class="flex flex-col sm:flex-row">
                        <div class="grid flex-1 grid-cols-1 gap-5 sm:grid-cols-2 ml-2">
                            <div>
                                <label for="product_name">Product Name</label>
                                <div class="relative text-white-dark mb-4">
                                    <?= input("text", "product_name", null, null, "form-input w-full", false, 'required') ?>
                                </div>
                            </div>
                            <div>
                                <label for="category_id">Category</label>
                                <div class="relative text-white-dark mb-4">
                                    <?= select("category_id", getCategoryOptions(), null, "Select Category", 'required') ?>
                                </div>
                            </div>
                            <div>
                                <label for="detailed_req">Detailed Requirements</label>
                                <div class="relative text-white-dark mb-4">
                                    <?= textarea("detailed_req", null, null, null, null, "3", null) ?>
                                </div>
                            </div>
                            <div>
                                <label for="quantity">Quantity</label>
                                <div class="relative text-white-dark mb-4">
                                    <?= input("number", "quantity", null, null, null, null, 'required') ?>
                                </div>
                            </div>
                            <div>
                                <label for="contact">Preferred Terms</label>
                                <div class="relative text-white-dark mb-4">
                                    <?= textarea("preferred_terms", null, null, null, null, "2", null) ?>
                                </div>
                            </div>
                            <div>
                                <label for="delivery_date">Delivery Date</label>
                                <div class="relative text-white-dark mb-4">
                                    <?= input("datetime-local", "delivery_date", null, null, null, null, 'required') ?>
                                </div>
                            </div>
                            <div>
                                <label for="vendors[]">Send to Vendors</label>
                                <div class="relative text-white-dark mb-4">
                                    <!-- Select All Checkbox -->
                                    <label class="flex items-center gap-2">
                                        <input type="checkbox" id="selectAll" class="accent-primary">
                                        <span>Select All</span>
                                    </label>

                                    <!-- Vendor Checkboxes -->
                                    <?php foreach ($vendors as $vendor): ?>
                                        <label class="flex items-center gap-2">
                                            <input type="checkbox" name="vendors[]" value="<?= $vendor['user_id'] ?>" class="vendor-checkbox accent-primary">
                                            <?= $vendor['company'] ?>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <div class="sm:col-span-2">
                                <?= button("submit", "btnCreate", "Create RFQ", null) ?>
                            </div>
                        </div>
                    </div>
                </form>
                <div id="responsePersonal"></div>
                <script>
                    $('#formPersonal').submit(function(e) {
                        e.preventDefault();
                        btnLoading('#btnLogin');
                        $.post('api/profile/update_personal.php', $('#formPersonal').serialize(), function(res) {
                            $('#responsePersonal').html(res);
                            btnLoadingReset('#btnLogin');
                        })
                    })
                </script>
            </div>
        </div>
    </div>
</div>

<script>
    
    // JavaScript to handle "Select All" functionality
    document.getElementById('selectAll').addEventListener('change', function() {
        const vendorCheckboxes = document.querySelectorAll('.vendor-checkbox');
        vendorCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });

    // Optional: Uncheck "Select All" if any vendor checkbox is unchecked
    const vendorCheckboxes = document.querySelectorAll('.vendor-checkbox');
    vendorCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            if (!this.checked) {
                document.getElementById('selectAll').checked = false;
            }
        });
    });
</script>