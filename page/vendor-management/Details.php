<?php

$vendor_id = $_GET['vendor_id']; // Get vendor ID
$products = GetVendorProducts($vendor_id);
if (empty($products)) die(toast("error", "Product not found on this vendor"));

$vendor = $DB->SELECT_ONE_WHERE('users', '*', ["user_id" => $vendor_id]); // Get user details by product_id
?>

<div class="page-content">

    <?php
        breadcrumb([
            ['label' => 'Home', 'url' => '/dashboard'],
            ['label' => 'Vendor Management', 'url' => '/vendor-management'],
            ['label' => 'Product List', 'url' => '/vendor-management/details'],
        ]);
    ?>  

    <div class="pt-5">
        <div class="panel h-full flex-col">
            <div class="text-lg font-bold">Vendor Name: <?=$vendor['company']?></div>
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
                                                alt="Profile picture" />
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
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    let table = new DataTable('#dataTable');
</script>



