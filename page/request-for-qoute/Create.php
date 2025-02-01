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

                    <!-- Products Container -->
                    <div id="productsContainer">
                        <!-- Initial Product Row -->
                        <div class="product-row border-b pb-4 mb-4">
                            <div class="flex justify-between items-center mb-3">
                                <h3 class="text-lg font-semibold">Product 1</h3>
                                <button type="button" class="btn btn-danger remove-product" style="display: none;">Remove</button>
                            </div>
                            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 ml-2">
                                <div>
                                    <label for="products[0][product_name]">Product Name</label>
                                    <div class="relative text-white-dark mb-4">
                                        <?= input("text", "products[0][product_name]", null, null, "form-input w-full", false, 'required') ?>
                                    </div>
                                </div>
                                <div>
                                    <label for="products[0][category_id]">Category</label>
                                    <div class="relative text-white-dark mb-4">
                                        <?= select("products[0][category_id]", getCategoryOptions(), null, "Select Category", 'required') ?>
                                    </div>
                                </div>
                                <div>
                                    <label for="products[0][detailed_req]">Detailed Requirements</label>
                                    <div class="relative text-white-dark mb-4">
                                        <?= textarea("products[0][detailed_req]", null, null, null, null, "3", null) ?>
                                    </div>
                                </div>
                                <div>
                                    <label for="products[0][quantity]">Quantity</label>
                                    <div class="relative text-white-dark mb-4">
                                        <?= input("number", "products[0][quantity]", null, null, null, null, 'required') ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Add Product Button -->
                    <div class="mb-4">
                        <button type="button" id="addProduct" class="btn btn-primary">
                            Add Another Product
                        </button>
                    </div>

                    <!-- Common Fields -->
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 ml-2">
                        <div>
                            <label for="preferred_terms">Preferred Terms</label>
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
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" id="selectAll" class="accent-primary">
                                    <span>Select All</span>
                                </label>
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
                </form>
                <div id="responseCreateRfq"></div>
            </div>
        </div>
    </div>
</div>

<script>
    let productCount = 1;

    // Add new product row
    document.getElementById('addProduct').addEventListener('click', function() {
        const container = document.getElementById('productsContainer');
        const newRow = document.createElement('div');
        newRow.className = 'product-row border-b pb-4 mb-4';

        newRow.innerHTML = `
            <div class="flex justify-between items-center mb-3">
                <h3 class="text-lg font-semibold">Product ${productCount + 1}</h3>
                <button type="button" class="btn btn-danger remove-product">Remove</button>
            </div>
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 ml-2">
                <div>
                    <label for="products[${productCount}][product_name]">Product Name</label>
                    <div class="relative text-white-dark mb-4">
                        <input type="text" name="products[${productCount}][product_name]" class="form-input w-full" required>
                    </div>
                </div>
                <div>
                    <label for="products[${productCount}][category_id]">Category</label>
                    <div class="relative text-white-dark mb-4">
                        <select name="products[${productCount}][category_id]" class="form-select" required>
                            <option value="">Select Category</option>
                            ${document.querySelector('select[name="products[0][category_id]"]').innerHTML}
                        </select>
                    </div>
                </div>
                <div>
                    <label for="products[${productCount}][detailed_req]">Detailed Requirements</label>
                    <div class="relative text-white-dark mb-4">
                        <textarea name="products[${productCount}][detailed_req]" rows="3" class="form-textarea"></textarea>
                    </div>
                </div>
                <div>
                    <label for="products[${productCount}][quantity]">Quantity</label>
                    <div class="relative text-white-dark mb-4">
                        <input type="number" name="products[${productCount}][quantity]" class="form-input" required>
                    </div>
                </div>
            </div>
        `;

        container.appendChild(newRow);
        productCount++;

        // Show remove button on first product if there's more than one product
        if (productCount > 1) {
            document.querySelector('.product-row .remove-product').style.display = 'block';
        }
    });

    // Remove product row
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-product')) {
            e.target.closest('.product-row').remove();
            productCount--;

            // Hide remove button on first product if only one product remains
            if (productCount === 1) {
                document.querySelector('.product-row .remove-product').style.display = 'none';
            }

            // Update product numbers
            document.querySelectorAll('.product-row h3').forEach((header, index) => {
                header.textContent = `Product ${index + 1}`;
            });
        }
    });

    // Handle form submission
    $('#formCreateRFQ').submit(function(e) {
        e.preventDefault();
        btnLoading('#btnCreate');
        $.post('../api/rfq/create.php', $(this).serialize(), function(res) {
            $('#responseCreateRfq').html(res);
            btnLoadingReset('#btnCreate');
        })
    });

    // Vendor selection
    document.getElementById('selectAll').addEventListener('change', function() {
        const vendorCheckboxes = document.querySelectorAll('.vendor-checkbox');
        vendorCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });

    const vendorCheckboxes = document.querySelectorAll('.vendor-checkbox');
    vendorCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            if (!this.checked) {
                document.getElementById('selectAll').checked = false;
            }
        });
    });
</script>