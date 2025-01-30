<?php
// Get all RFQs
$rfqs = $DB->SELECT_JOIN(
    ['rfq_requests', 'users'],
    't1.*, t2.company',
    [
        [['t1.created_by', 't2.user_id']]
    ],
    ['INNER JOIN'],
    [],
    'ORDER BY request_date DESC'
);
?>

<div class="page-content">
    <?php
    breadcrumb([
        ['label' => 'Home', 'url' => '/dashboard'],
        ['label' => 'RFQ Management', 'url' => '/request-for-qoute'],
    ]);
    ?>
    <div class="pt-5">
        <div class="panel">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold">Active RFQs</h3>
                <a href="/request-for-qoute/create" class="btn btn-primary">+ New RFQ</a>
            </div>

            <div class="table-responsive  min-h-[400px] grow overflow-y-auto sm:min-h-[300px]">
                <table id="dataTable" class="table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>RFQ ID</th>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Delivery Date</th>
                            <th>Status</th>
                            <th>Responses</th>
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
                                <td><?= badge($rfq['status']) ?></td>
                                <td>
                                    <?php
                                    $responses = $DB->SELECT_WHERE('rfq_responses', 'COUNT(*) AS count', [
                                        'rfq_id' => $rfq['rfq_id']
                                    ]);
                                    echo $responses[0]['count'];
                                    ?>
                                </td>
                                <td>
                                    <a href="/request-for-qoute/details?id=<?= $rfq['rfq_id'] ?>" class="btn btn-sm btn-info">
                                        View Details
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
            [0, 'desc']
        ]
    });
</script>