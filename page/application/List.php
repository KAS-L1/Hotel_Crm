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
        <div class="panel">
            <div class="table-responsive">
                <table id="dataTable" class="table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th class="whitespacce-nowrap">Vendor Name</th>
                            <th>Email</th>
                            <th>Vendor Company</th>
                            <th>Status</th>
                            <th>Submited Date</th>
                            <th>Updated Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($applications as $application): ?>
                            <tr>
                                <td><?= $application['id'] ?></td>
                                <td class="whitespace-nowrap py-2 px-3">
                                    <div class="flex flex-col sm:flex-row items-center sm:items-start whitespace-normal">
                                        <img class="h-12 w-12 rounded-md object-cover sm:mr-2 mb-2 sm:mb-0 mr-1"
                                            src="<?= DOMAIN ?>/upload/profile/<?= $application['picture'] ?>"
                                            alt="image">
                                        <span class="text-center sm:text-left"><?= $application['first_name'] . ' ' . $application['last_name'] ?></span>
                                    </div>
                                </td>
                                <td><?= $application['email'] ?></td>
                                <td><?= $application['company'] ?></td>
                                <td><?= $application['status'] ?></td>
                                <td><?= $application['created_at'] ?></td>
                                <td><?= $application['updated_at'] ?></td>
                                <td class="py-2 px-3 text-center">
                                    <a href="/application/details?application_id=<?= $application['user_id'] ?>" x-tooltip="Details" class="text-primary inline-flex justify-center items-center">
                                        <i class="fa fa-eye"></i>
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
    let table = new DataTable('#dataTable');
</script>