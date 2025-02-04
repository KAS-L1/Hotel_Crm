<?php

// Get all contracts
$contracts = $DB->SELECT_JOIN(
    ['contracts', 'users', 'rfq_requests'],
    't1.*, t2.company AS vendor_company, t3.product_name',
    [
        [['t1.vendor_id', 't2.user_id']],
        [['t1.rfq_id', 't3.rfq_id']]
    ],
    ['INNER JOIN', 'INNER JOIN'],
    [],
    'ORDER BY t1.created_at DESC'
);
?>


<div class="page-content">
    <?php breadcrumb([
        ['label' => 'Home', 'url' => '/dashboard'],
        ['label' => 'Contract Management', 'url' => '/contract'],
    ]); ?>
    <div class="pt-5">
        <div class="panel">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold">Contract Management</h3>
            </div>
            <!-- Contract Table -->
            <div class="table-responsive  min-h-[400px] grow overflow-y-auto sm:min-h-[300px]">
                <table id="dataTable" class="table-hover table-bordered">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Contract ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vendor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">RFQ Product</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Renewal Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Expiration Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($contracts as $contract): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div><?= $contract['contract_id'] ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?= $contract['vendor_company'] ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?= $contract['product_name'] ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                                        <?= badge($contract['status']); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                                        <?= badge($contract['renewal_status']); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php if ($contract['expiration_date'] == '0000-00-00'): ?>
                                        <span class="text-gray-500">N/A</span>
                                    <?php else: ?>
                                        <?= date('M d, Y', strtotime($contract['expiration_date'])) ?>
                                    <?php endif; ?>
                                </td>
                                <td class="py-2 px-3 text-center">
                                    <div class="flex justify-center items-center space-x-2">

                                        <?php if ($contract['status'] === 'Pending Approval' && !empty($contract['contract_file'])): ?>
                                            <form class="inline formContractApprove">
                                                <?= csrfProtect('generate') ?>
                                                <input type="hidden" name="contract_id" value="<?= $contract['contract_id'] ?>">
                                                <input type="hidden" name="approve_contract" value="1"> <!-- Add this line -->
                                                <button type="submit" class="text-indigo-600 hover:text-indigo-900" x-tooltip="Approve Contract">
                                                    <i class="fas fa-check btn-lg"></i>
                                                </button>
                                            </form>
                                        <?php elseif ($contract['status'] === 'Approved'): ?>
                                            <button type="button" id="contract-<?= $contract['id'] ?>" class="text-dark hover:text-danger-dark btnEditContract" x-tooltip="Edit" data-toggle-modal="editContractModal" data-id="<?= $contract['id'] ?>" data-expiration_date="<?= $contract['expiration_date'] ?>">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </button>
                                        <?php endif; ?>


                                        <?php if (empty($contract['contract_file'])): ?>
                                            <button class="text-primary btnOpenModalUpload" x-tooltip="Upload" data-contract_id="<?= $contract['contract_id'] ?>">
                                                <i class="fas fa-upload btn-lg"></i>
                                            </button>
                                        <?php else: ?>
                                            <a href="<?= $contract['contract_file'] ?>" x-tooltip="Download" class="text-gray-600 hover:text-gray-900"
                                                download>
                                                <i class="fas fa-download btn-lg"></i>
                                            </a>
                                        <?php endif; ?>
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

<!-- Edit Contract Modal -->
<div id="editContractModal" class="fixed inset-0 bg-[black]/60 z-[999] hidden overflow-y-auto flex items-start justify-center min-h-screen px-4">
    <div class="panel border-0 py-1 px-4 rounded-lg overflow-hidden w-full max-w-sm my-8">
        <div class="flex items-center justify-between p-5 font-semibold text-lg dark:text-white">
            Edit Contract
            <button type="button" data-toggle-modal="editContractModal" class="text-white-dark hover:text-dark closeModal">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>
        <div class="p-5">
            <div id="responseEditContract"></div>
            <form id="formEditContract" class="space-y-4">
                <?= csrfProtect('generate') ?>
                <input type="text" name="contract_id" id="editContractId" value="" hidden>
                <div class="relative mb-4">
                    <input type="text" name="editContractExpirationDate" id="editContractExpirationDate" class="form-input w-full datepicker" placeholder="Select Date">
                </div>
                <div class="flex justify-end gap-2">
                    <?= button("button", "btnCancel", "Cancel", "btn btn-danger closeModal", false, 'data-toggle-modal="editContractModal"') ?>
                    <?= button("submit", "btnEditContractSave", "Edit", "btn btn-primary", false) ?>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="responseContractModal"></div>

<script>
    let table = new DataTable('#dataTable', {
        order: [
            [0, 'desc']
        ]
    });

    // Initialize Flatpickr
    flatpickr("#editContractExpirationDate", {
        dateFormat: "Y-m-d", // Format the date as needed
        minDate: "today", // Optional: Restrict selection to today or later
        allowInput: true, // Allow manual input
    });

    function openModal(id) {
        $('#' + id).removeClass('hidden');
    }

    function closeModal(id) {
        $('#' + id).addClass('hidden');
    }

    $('.btnOpenModalUpload').click(function() {
        const contract_id = $(this).data('contract_id');
        $.post('../api/contract/modal_upload.php', {
            contract_id: contract_id
        }, function(res) {
            $('#responseContractModal').html(res);
            $('#modalContract').removeClass('hidden');
        });
    });

    $('.formContractApprove').submit(function(e) {
        e.preventDefault();
        const form = $(this);
        btnLoading(form.find('button'));

        $.ajax({
            url: '../api/contract/upload_contract.php',
            method: 'POST',
            data: form.serialize(),
            success: function(res) {
                $('#responseContractModal').html(res);
                if (res.includes('success')) {
                    setTimeout(() => window.location.reload(), 2000);
                }
                btnLoadingReset(form.find('button'));
            },
            error: function(xhr) {
                $('#responseContractModal').html('<div class="alert alert-danger">Error approving contract</div>');
                btnLoadingReset(form.find('button'));
            }
        });
    });

    $('.btnEditContract').on('click', function() {
        const contract_id = $(this).data('id');
        const expiration_date = $(this).data('expiration_date');
        $('#editContractModal').removeClass('hidden');
        $('#editContractExpirationDate').val(expiration_date);
        $('#editContractId').val(contract_id);
    });

    $('#btnEditContractSave').click(function(e) {
        e.preventDefault();
        const form = $('#formEditContract');
        btnLoading('#btnEditContractSave');

        $.ajax({
            url: '../api/contract/edit_contract.php',
            method: 'POST',
            data: form.serialize(),
            success: function(res) {
                $('#responseEditContract').html(res);
                btnLoadingReset('#btnEditContractSave');
                if (res.includes('success')) {
                    setTimeout(() => window.location.reload(), 2000);
                }
            },
            error: function(xhr) {
                $('#responseEditContract').html('<div class="alert alert-danger">Error editing contract</div>');
                btnLoadingReset('#btnEditContractSave');
            }
        });
    });

    // Close modal on button click
    $('.closeModal').click(function() {
        const modalId = $(this).data('toggle-modal');
        closeModal(modalId);
    });
</script>