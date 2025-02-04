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
                                        <?= date('M d, Y', strtotime($contract['expiration_date'])) ?>
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

    <div id="responseContractModal"></div>

    <script>
        let table = new DataTable('#dataTable', {
            order: [
                [0, 'desc']
            ]
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
    </script>