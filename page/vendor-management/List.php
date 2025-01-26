<?php

$vendors = $DB->SELECT_WHERE('users', '*', ['status' => 'Active', 'role' => 'Vendor']);

?>

<div class="page-content">

    <?php
    breadcrumb([
        ['label' => 'Home', 'url' => '/dashboard'],
        ['label' => 'Vendor Management', 'url' => '/vendor-management'],
    ]);
    ?>

    <div class="pt-5">
        <div class="panel h-full flex-col">
            <div class="table-responsive  min-h-[400px] grow overflow-y-auto sm:min-h-[300px]">
                <table id="dataTable" class="table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Vendor ID</th>
                            <th>Name</th>
                            <th>Company</th>
                            <th>Email</th>
                            <th>Contact</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($vendors as $vendor): ?>
                            <tr>
                                <td><?= $vendor['id'] ?></td>
                                <td><?= $vendor['user_id'] ?></td>
                                <td class="p-2 lg:p-4">
                                    <div class="flex flex-col sm:flex-row items-center gap-3">
                                        <div class="w-12 h-12 min-w-12 shrink-0">
                                            <img
                                                class="w-full h-full rounded-md object-cover"
                                                src="<?= DOMAIN ?>/upload/profile/<?= $vendor['picture'] ?>"
                                                alt="Profile picture" />
                                        </div>
                                        <div class="text-sm sm:text-base text-center sm:text-left break-words max-w-[200px]">
                                            <?= $vendor['first_name'] . ' ' . $vendor['last_name'] ?>
                                        </div>
                                    </div>
                                </td>
                                <td><?= $vendor['company'] ?></td>
                                <td><?= $vendor['email'] ?></td>
                                <td><?= $vendor['contact'] ?></td>
                                <td class="py-2 px-3 text-center">
                                    <a href="<?=DOMAIN?>/vendor-management/details?vendor_id=<?= $vendor['user_id'] ?>" x-tooltip="Details" class="text-primary inline-flex justify-center items-center">
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