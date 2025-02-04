<?php
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
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">RFQ ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Delivery Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Responses</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
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
                                <td class="py-2 px-3 text-center">
                                    <div class="flex justify-center items-center">
                                        <a href="/request-for-qoute/details?id=<?= $rfq['rfq_id'] ?>"
                                            x-tooltip="Details"
                                            class="text-primary">
                                            <i class="fa fa-eye text-lg"></i>
                                        </a>
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
    let table = new DataTable('#dataTable', {
        order: [
            [4, 'desc']
        ]
    });
</script>