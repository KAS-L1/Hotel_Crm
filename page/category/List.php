<?php


$categories = $DB->SELECT('product_categories', '*', 'Order by id Desc');

?>

<div class="page-content">

    <?php
    breadcrumb([
        ['label' => 'Home', 'url' => '/dashboard'],
        ['label' => 'Category Management', 'url' => '/category'],
    ]);
    ?>

    <div class="pt-5">
        <div class="panel h-full flex-col">
            <div class="table-responsive  min-h-[400px] grow overflow-y-auto sm:min-h-[300px]">
                <table id="dataTable" class="table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Category ID</th>
                            <th>Name</th>
                            <th>Created At</th>
                            <th>Updated At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $category): ?>
                            <tr>
                                <td><?= $category['id'] ?></td>
                                <td><?= $category['category_id'] ?></td>
                                <td><?= $category['category'] ?></td>
                                <td><?= DATE_TIME_SHORT($category['created_at']) ?></td>
                                <td><?= DATE_TIME_SHORT($category['updated_at'] ?? '') ?></td>
                                <td class="py-2 px-3 text-center flex justify-center items-center gap-2 text-lg">
                                    <button type="button" class="text-dark hover:text-danger-dark btnEdit" x-tooltip="Edit" data-category_id="<?= $category['id'] ?>" title="Edit">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                    <button type="button" class="text-danger hover:text-danger-dark btnDelete" x-tooltip="Delete" data-category_id="<?= $category['id'] ?>" title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
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