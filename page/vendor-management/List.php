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
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vendor ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Company</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Contact</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
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
                                    <div class="flex justify-center items-center">
                                        <a href="/vendor-management/details?vendor_id=<?= $vendor['user_id'] ?>" x-tooltip="Details" class="text-primary">
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