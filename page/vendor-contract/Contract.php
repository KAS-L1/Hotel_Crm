    <?php
    $contracts = $DB->SELECT_JOIN(
        ['contracts', 'rfq_requests'],
        't1.*, t2.product_name',
        [[['t1.rfq_id', 't2.rfq_id']]],
        ['INNER JOIN'],
        ['t1.vendor_id' => AUTH_USER_ID],
        'ORDER BY t1.created_at DESC'
    );

    ?>

    <div class="page-content">
        <?php breadcrumb([
            ['label' => 'Home', 'url' => '/dashboard'],
            ['label' => 'Vendor Contract', 'url' => '/vendor-contract'],
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
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">RFQ Product</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Renewal Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Expiration Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Expiration Period</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Expiration Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $expire_day = 0;
                                foreach ($contracts as $contract):
                                    $date1 = new DateTime(date('Y-m-d'));
                                    $date2 = new DateTime($contract['expiration_date']);
                                    $interval = $date1->diff($date2);
                                    $expire_label = $interval->format('%y years, %m months, %d days');
                                    $expire_day = $interval->format('%d');
                                
                                    if($expire_day <= 0) {
                                        $DB->UPDATE('contracts', ['is_expired' => 1], ['contract_id' => $contract['contract_id']]);
                                    }

                            ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div><?= $contract['contract_id'] ?></div>
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
                                    <td>
                                        <?= $expire_label ?>
                                    </td>
                                    <td>
                                        <?php if ($contract['is_expired'] == 0) { ?>
                                            <?= badge("Active") ?>
                                        <?php } else { ?>
                                            <?= badge("Expired") ?>
                                        <?php } ?>
                                    </td>
                                    <td class="py-2 px-3 text-center">
                                        <div class="flex justify-center items-center space-x-2">
                                            <?php if ($contract['is_signed'] == 0) { ?>
                                                <a href="<?= ROUTE('upload/contract/' . $contract['contract_file']) ?>" x-tooltip="Download" download>Download</a>
                                                <button class="btnUploadSigned" x-tooltip="Upload Signed" data-id="<?= $contract['contract_id'] ?>">
                                                    Upload Signed
                                                </button>
                                            <?php } else { ?>
                                                <?= badge("Signed"); ?>
                                            <?php } ?>
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

    <div id="responseSignedModal"></div>
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

        $('.btnUploadSigned').click(function() {
            const contract_id = $(this).data('id');
            $.post('../api/contract/modal_upload_signed.php', {
                contract_id: contract_id
            }, function(res) {
                $('#responseSignedModal').html(res);
                $('#modalUploadSigned').removeClass('hidden');
            });
        });
    </script>