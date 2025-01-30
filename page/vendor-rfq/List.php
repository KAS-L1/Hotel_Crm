<?php

// Get RFQs where vendor was notified
$rfqs = $DB->SELECT_JOIN(
    ['notifications', 'rfq_requests'],
    't2.*',
    [
        [['t1.action', 'CONCAT("/vendor-rfq/details?id=", t2.rfq_id)']]
    ],
    ['INNER JOIN'],
    [
        't1.user_id' => AUTH_USER_ID,
        't1.status' => 'Unread'
    ]
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
            <div class="table-responsive  min-h-[400px] grow overflow-y-auto sm:min-h-[300px]">
                <table id="dataTable" class="table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>RFQ ID</th>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Delivery Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rfqs as $rfq): ?>
                            <tr>
                                <td><?= $rfq['rfq_id'] ?></td>
                                <td><?= $rfq['product_name'] ?></td>
                                <td><?= $rfq['quantity'] ?></td>
                                <td><?= date('M d, Y', strtotime($rfq['delivery_date'])) ?></td>
                                <td>
                                    <a href="/vendor-rfq/details?id=<?= $rfq['rfq_id'] ?>" class="btn btn-sm btn-primary">
                                        View & Respond
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
            [4, 'desc']
        ]
    });
</script>