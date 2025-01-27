<?php
$categories = $DB->SELECT('product_categories', '*', 'Order by id Desc');
?>

<div class="page-content">
    <?php breadcrumb([
        ['label' => 'Home', 'url' => '/dashboard'],
        ['label' => 'Category Management', 'url' => '/category'],
    ]); ?>

    <div class="pt-5">
        <div class="panel h-full flex-col">
            <!-- Modal Trigger Button -->
            <button type="button" class="btn btn-primary mb-4" onclick="toggleModal()">+ Create Category</button>

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
                                        <button type="button" class="text-dark hover:text-danger-dark btnEdit" data-category_id="<?= $category['id'] ?>">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                        <button type="button" class="text-danger hover:text-danger-dark btnDelete" data-category_id="<?= $category['id'] ?>">
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

<!-- Modal -->
<div id="categoryModal" class="fixed inset-0 bg-[black]/60 z-[999] hidden overflow-y-auto">
    <div class="flex items-start justify-center min-h-screen px-4" onclick="closeModalOnBackdrop(event)">
        <div class="panel border-0 py-1 px-4 rounded-lg overflow-hidden w-full max-w-sm my-8">
            <div class="flex items-center justify-between p-5 font-semibold text-lg dark:text-white">
                Create Category
                <button type="button" onclick="toggleModal()" class="text-white-dark hover:text-dark">
                    <i class="fa-solid fa-times"></i>
                </button>
            </div>
            <div class="p-5">
                <div id="responseCategory"></div>
                <form id="formCategory" class="space-y-4">
                    <?= csrfProtect('generate') ?>
                    <div class="relative mb-4">
                        <?= input("text", "category", null, "Category Name", "form-input w-full", false) ?>
                    </div>
                    <div class="flex justify-end gap-2">
                        <?= button("button", "btnCancel", "Cancel", "btn btn-danger", false, 'onclick="toggleModal()"') ?>
                        <?= button("submit", "btnCreate", "Create", "btn btn-primary", false) ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    let table = new DataTable('#dataTable');

    function toggleModal() {
        const modal = document.getElementById('categoryModal');
        modal.classList.toggle('hidden');
    }

    function closeModalOnBackdrop(event) {
        if (event.target === event.currentTarget) {
            toggleModal();
        }
    }

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