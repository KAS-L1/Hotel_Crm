<?php

$vendor_id = AUTH_USER_ID; // Get vendor ID
$products = GetVendorProducts($vendor_id);
if (empty($products)) toast("error", "Product not found on this vendor");

$vendor = $DB->SELECT_ONE_WHERE('users', '*', ["user_id" => $vendor_id]); // Get user details by product_id
?>

<div class="page-content">

    <?php
    breadcrumb([
        ['label' => 'Home', 'url' => '/dashboard'],
        ['label' => 'Product Catalog', 'url' => '/vendor-catalog'],
    ]);
    ?>

    <div class="pt-5">
        <div class="panel h-full flex-col">
            <div class="text-lg font-bold">Vendor Name: <?= $vendor['company'] ?></div>
            <div class="flex justify-end">
                <a href="/vendor-catalog/create" class="btn btn-primary mb-4">+ Create Product</a>
            </div>
            <div class="table-responsive  min-h-[400px] grow overflow-y-auto sm:min-h-[300px]">
                <table id="dataTable" class="table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Product ID</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Unit Price</th>
                            <th>Stock</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                            <tr>
                                <td><?= $product['id'] ?></td>
                                <td><?= $product['product_id'] ?></td>
                                <td class="p-2 lg:p-4">
                                    <div class="flex flex-col sm:flex-row items-center gap-3">
                                        <div class="w-12 h-12 min-w-12 shrink-0">
                                            <img
                                                class="w-full h-full rounded-md object-cover"
                                                src="<?= DOMAIN ?>/upload/product/<?= $product['image'] ?>"
                                                alt="Product Image" />
                                        </div>

                                        <div class="text-sm sm:text-base text-center sm:text-left break-words max-w-[200px]">
                                            <?= $product['name'] ?>
                                        </div>
                                    </div>
                                </td>
                                <td><?= $product['category'] ?></td>
                                <td><?= $product['description'] ?></td>
                                <td><?= badge($product['status']); ?></td>
                                <td><?= PESO($product['unit_price']); ?></td>
                                <td><?= $product['stock'] ?></td>
                                <td class="py-2 text-center">
                                    <div class="flex justify-center items-center gap-2 text-lg">
                                        <a href="/vendor-catalog/edit?product_id=<?= $product['product_id'] ?>" class="text-dark hover:text-danger-dark">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <button type="button" class="text-danger hover:text-danger-dark btnDelete" data-product_id="<?= $product['product_id'] ?>">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="responseProductDelete"></div>
<script>
    let table = new DataTable('#dataTable', {
        order: [
            [0, 'desc']
        ]
    });

    // Delete Product
    $('.btnDelete').click(function() {
        if (confirm('Are you sure you want to delete this product?')) {
            const product_id = $(this).data('product_id');

            $.post("../api/vendor-catalog/delete.php", {
                product_id: product_id,
            }, function(res) {
                $('.responseProductDelete').html(res);
            }).fail(function(xhr) {
                $('.responseProductDelete').html('<div class="alert alert-danger">An error occurred. Please try again.</div>');
            });
        }
    });
</script>