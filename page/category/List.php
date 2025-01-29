<?php
$categories = $DB->SELECT('product_categories', '*', 'ORDER BY id DESC');
?>

<div class="page-content">
    <?php breadcrumb([
        ['label' => 'Home', 'url' => '/dashboard'],
        ['label' => 'Category Management', 'url' => '/category'],
    ]); ?>

    <div class="pt-5">
        <div class="panel h-full flex-col">
            <div class="flex justify-end">
                <button type="button" class="btn btn-primary mb-4" data-toggle-modal="createModal">+ Create Category</button>
            </div>
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
                                <td><?= $category['created_at'] ?></td>
                                <td><?= $category['updated_at'] ?></td>
                                <td class="py-2 text-center">
                                    <div class="flex justify-center items-center gap-2 text-lg">
                                        <button type="button" class="text-dark hover:text-danger-dark btnEdit" data-toggle-modal="editModal" data-category_id="<?= $category['id'] ?>">
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

<!-- Create Category Modal -->
<div id="createCategoryModal" class="fixed inset-0 bg-[black]/60 z-[999] hidden overflow-y-auto flex items-start justify-center min-h-screen px-4">
    <div class="panel border-0 py-1 px-4 rounded-lg overflow-hidden w-full max-w-sm my-8">
        <div class="flex items-center justify-between p-5 font-semibold text-lg dark:text-white">
            Create Category
            <button type="button" data-toggle-modal="createModal" class="text-white-dark hover:text-dark">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>
        <div class="p-5">
            <div id="responseCreateCategory"></div>
            <form id="formCreateCategory" class="space-y-4">
                <?= csrfProtect('generate') ?>
                <div class="relative mb-4">
                    <?= input("text", "category", null, "Category Name", "form-input w-full", false) ?>
                </div>
                <div class="flex justify-end gap-2">
                    <?= button("button", "btnCancel", "Cancel", "btn btn-danger", false, 'data-toggle-modal="createModal"') ?>
                    <?= button("submit", "btnCreate", "Create", "btn btn-primary", false) ?>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Category Modal -->
<div id="editCategoryModal" class="fixed inset-0 bg-[black]/60 z-[999] hidden overflow-y-auto flex items-start justify-center min-h-screen px-4">
    <div class="panel border-0 py-1 px-4 rounded-lg overflow-hidden w-full max-w-sm my-8">
        <div class="flex items-center justify-between p-5 font-semibold text-lg dark:text-white">
            Edit Category
            <button type="button" data-toggle-modal="editModal" class="text-white-dark hover:text-dark">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>
        <div class="p-5">
            <div id="responseEditCategory"></div>
            <form id="formEditCategory" class="space-y-4">
                <?= csrfProtect('generate') ?>
                <div class="relative mb-4">
                    <?= input("text", "category", null, "Category Name", "form-input w-full", false) ?>
                </div>
                <div class="flex justify-end gap-2">
                    <?= button("button", "btnCancel", "Cancel", "btn btn-danger", false, 'data-toggle-modal="editModal"') ?>
                    <?= button("submit", "btnEdit", "Edit", "btn btn-primary", false) ?>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="responseCategoryDelete"></div>

<script>
    // Handle Create Modal Toggle
    document.addEventListener("click", (event) => {
        const createModal = document.getElementById("createCategoryModal");
        const editModal = document.getElementById("editCategoryModal");

        if (event.target.closest("[data-toggle-modal='createModal']")) {
            createModal.classList.toggle("hidden");
            editModal.classList.add("hidden");
        }
        if (event.target === createModal) {
            createModal.classList.add("hidden");
        }
    });

    // Handle Edit Modal Toggle
    document.addEventListener("click", (event) => {
        const editModal = document.getElementById("editCategoryModal");
        const createModal = document.getElementById("createCategoryModal");

        if (event.target.closest("[data-toggle-modal='editModal']")) {
            editModal.classList.toggle("hidden");
            createModal.classList.add("hidden");
        }
        if (event.target === editModal) {
            editModal.classList.add("hidden");
        }
    });

    // Create Category Form Submission
    $('#formCreateCategory').submit(function(e) {
        e.preventDefault();
        btnLoading('#btnCreate');
        $.post('api/category/create.php', $(this).serialize(), function(res) {
            $('#responseCreateCategory').html(res);
            btnLoadingReset('#btnCreate');
        }).fail(function() {
            $('#responseCreateCategory').html('An error occurred. Please try again.');
            btnLoadingReset('#btnCreate');
        });
    });

    // Edit Category Button Click
    $(document).on('click', '.btnEdit', function() {
        const categoryId = $(this).data('category_id');
        $('#formEditCategory input[name="category_id"]').remove();

        $.post('api/category/edit.php', {
            category_id: categoryId,
            csrf_token: $('[name="csrf_token"]').val()
        }, function(res) {
            const data = JSON.parse(res);
            if (data.status === 'success') {
                $('#formEditCategory input[name="category"]').val(data.category.category);
                $('#formEditCategory').append(`<input type="hidden" name="category_id" value="${categoryId}">`);
                $('#editCategoryModal').removeClass('hidden');
            } else {
                alert(data.message || 'Failed to fetch category data.');
            }
        }).fail(function(xhr) {
            alert('An error occurred: ' + xhr.status + ' ' + xhr.statusText);
        });
    });

    // Edit Category Form Submission
    $('#formEditCategory').submit(function(e) {
        e.preventDefault();
        btnLoading('#btnEdit');
        $.post('api/category/update.php', $(this).serialize(), function(res) {
            $('#responseEditCategory').html(res);
            btnLoadingReset('#btnEdit');
        }).fail(function() {
            $('#responseEditCategory').html('An error occurred. Please try again.');
            btnLoadingReset('#btnEdit');
        });
    });

    // Delete Category
    $('.btnDelete').click(function() {
        if (confirm('Are you sure you want to delete this category?')) {
            const categoryId = $(this).data('category_id');
            $.post("api/category/delete.php", {
                category_id: categoryId,
                csrf_token: $('[name="csrf_token"]').val()
            }, function(res) {
                $('.responseCategoryDelete').html(res);
            }).fail(function(xhr) {
                $('.responseCategoryDelete').html('An error occurred. Please try again.');
            });
        }
    });


    // Initialize DataTable
    let table = new DataTable('#dataTable', {
        order: [
            [0, 'desc']
        ]
    });
</script>