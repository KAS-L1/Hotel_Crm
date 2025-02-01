<?php
// Get RFQs where vendor was notified, grouped by rfq_group_id
$rfqs = $DB->SELECT_JOIN(
    ['notifications', 'rfq_requests'],
    't2.*, COUNT(t2.rfq_id) as product_count',
    [
        [['t1.action', 'CONCAT("/vendor-rfq/details?group_id=", t2.rfq_group_id)']]
    ],
    ['INNER JOIN'],
    [
        't1.user_id' => AUTH_USER_ID,
        't1.status' => 'Unread'
    ],
    'GROUP BY t2.rfq_group_id'
);
?>

<div class="page-content">
    <?php
    breadcrumb([
        ['label' => 'Home', 'url' => '/dashboard'],
        ['label' => 'RFQ Management', 'url' => '/vendor-rfq'],
    ]);
    ?>
    <div class="pt-5">
        <div class="panel">
            <div class="text-xl font-bold mb-4">Active RFQs</div>
            <div class="table-responsive min-h-[400px] grow overflow-y-auto sm:min-h-[300px]">
                <table id="dataTable" class="table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>RFQ ID</th>
                            <th>Products</th>
                            <th>Delivery Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rfqs as $rfq): ?>
                            <tr>
                                <td><?= $rfq['rfq_group_id'] ?></td>
                                <td>
                                    <?php if ($rfq['product_count'] > 1): ?>
                                        <div class="flex flex-col">
                                            <span class="font-semibold">Bulk RFQ - <?= $rfq['product_count'] ?> Products</span>
                                            <span class="text-sm text-gray-500">Including: <?= $rfq['product_name'] ?></span>
                                        </div>
                                    <?php else: ?>
                                        <div class="flex flex-col">
                                            <span><?= $rfq['product_name'] ?></span>
                                            <span class="text-sm">Qty: <?= $rfq['quantity'] ?></span>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td><?= date('M d, Y', strtotime($rfq['delivery_date'])) ?></td>
                                <td>
                                    <a href="/vendor-rfq/details?group_id=<?= $rfq['rfq_group_id'] ?>"
                                        class="btn btn-sm btn-primary">
                                        <?= $rfq['product_count'] > 1 ? 'View All & Respond' : 'View & Respond' ?>
                                    </a>
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
    let table = new DataTable('#dataTable', {
        order: [
            [2, 'desc']
        ] // Order by delivery date
    });
</script>