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
            <div id="responseCategory"></div>
            <form id="formCategory" class="space-y-4 dark:text-white">
                <?= csrfProtect('generate') ?>
                <div class="w-full sm:w-2/3 md:w-1/3 mx-auto"> <!-- Simplified form width -->
                    <?= input("text", "category", null, "Category Name", "w-full p-2 border rounded", false) ?>
                </div>
                <div class="w-full sm:w-2/3 md:w-1/3 mx-auto"> <!-- Aligned button with input -->
                    <?= button("submit", "btnCreate", "+ Create Category", "w-full p-2 mt-3 bg-blue-500 text-white rounded", false) ?>
                </div>
            </form>

            <div class="table-responsive overflow-y-auto mt-4">
                <table id="dataTable" class="table-bordered table-hover w-full text-sm">
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
                                <td class="py-2 text-center">
                                    <div class="flex justify-center items-center gap-2 text-lg">
                                        <button type="button" class="text-dark hover:text-danger-dark btnEdit" x-tooltip="Edit" data-category_id="<?= $category['id'] ?>" title="Edit">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                        <button type="button" class="text-danger hover:text-danger-dark btnDelete" x-tooltip="Delete" data-category_id="<?= $category['id'] ?>" title="Delete">
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

<script>
    let table = new DataTable('#dataTable');
</script>

<script>
    $('#formCategory').submit(function(e) {
        e.preventDefault();
        btnLoading('#btnCreate');
        $.post('api/category/create.php', $('#formCategory').serialize(), function(res) {
            $('#responseCategory').html(res);
            btnLoadingReset('#btnCreate');
        }).fail(function() {
            $('#responseCategory').html('An error occurred. Please try again.');
        });
    });
</script>