<div class="page-content">
    <?php
    $contracts = $DB->SELECT_JOIN(
        ['contracts', 'rfq_requests'],
        't1.*, t2.product_name',
        [[['t1.rfq_id', 't2.rfq_id']]],
        ['INNER JOIN'],
        ['t1.vendor_id' => $_SESSION['user']['user_id'], 't1.signed_contract_file' => ''],
        'ORDER BY t1.created_at DESC'
    );
    ?>

    <table>
        <!-- Table headers -->
        <tbody>
            <?php foreach ($contracts as $contract): ?>
                <tr>
                    <td><?= $contract['contract_id'] ?></td>
                    <td>
                        <a href="<?= $contract['contract_file'] ?>" download>Download</a>
                        <button class="btn-upload-signed" data-id="<?= $contract['contract_id'] ?>">
                            Upload Signed
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>