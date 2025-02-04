<?php

$applications = $DB->SELECT_JOIN(
    ['users', 'vendors_application'],
    't1.user_id, t1.first_name, t1.last_name, t1.address, t1.email, t1.company, t1.picture, t2.id, t2.status, t2.created_at, t2.updated_at, t2.updated_at',
    [
        [['t1.user_id', 't2.vendor_id']]
    ],
    ['INNER JOIN'],
    null,
    'ORDER BY t2.id DESC'

);

?>

<div class="page-content">

    <?php
    breadcrumb([
        ['label' => 'Home', 'url' => '/dashboard'],
        ['label' => 'Application', 'url' => '/application'],
    ]);
    ?>

    <div class="pt-5">
        <div class="panel h-full flex-col">
            <div class="table-responsive  min-h-[400px] grow overflow-y-auto sm:min-h-[300px]">
                <table id="dataTable" class="table-bordered table-hover">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vendor Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vendor Company</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Submited Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Updated Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($applications as $application): ?>
                            <tr>
                                <td><?= $application['id'] ?></td>
                                <td class="p-2 lg:p-4">
                                    <div class="flex flex-col sm:flex-row items-center gap-3">
                                        <div class="w-12 h-12 min-w-12 shrink-0">
                                            <img
                                                class="w-full h-full rounded-md object-cover"
                                                src="<?= DOMAIN ?>/upload/profile/<?= $application['picture'] ?>"
                                                alt="Profile picture" />
                                        </div>
                                        <div class="text-sm sm:text-base text-center sm:text-left break-words max-w-[200px]">
                                            <?= $application['first_name'] . ' ' . $application['last_name'] ?>
                                        </div>
                                    </div>
                                </td>
                                <td><?= $application['email'] ?></td>
                                <td><?= $application['company'] ?></td>
                                <td><?= $application['status'] ?></td>
                                <td><?= $application['created_at'] ?></td>
                                <td><?= $application['updated_at'] ?></td>
                                <td class="py-2 px-3 text-center">
                                    <div class="flex justify-center items-center">
                                        <a href="/application/details?application_id=<?= $application['user_id'] ?>"
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
            [0, 'desc']
        ]
    });
</script>