<?php
$users = $DB->SELECT("users", "*", "ORDER BY created_at DESC");
?>

<div class="d-flex justify-content-between mb-2">
    <h4 class="app__page-title"><i class="bi bi-people"></i> List of Users</h4>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= DOMAIN ?>/dashboard">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Users</li>
        </ol>
    </nav>
</div>

<div class="card card-body">
    <table id="dataTable" class="table table-hover">
        <thead class="table-secondary">
            <tr>
                <th class="text-start">Action</th>
                <th class="text-start">Username</th>
                <th class="text-start">Name</th>
                <th class="text-start">Email</th>
                <th class="text-start">Contact</th>
                <th class="text-start">Role</th>
                <th class="text-end">Created</th>
            </tr>
        </thead>
        <tbody>

            <?php $i = 1;
            foreach ($users as $user) { ?>
                <tr>
                    <td class="text-start">
                        <a href="./users/details?uid=<?= $user['user_id'] ?>" class="btn btn-sm btn-light"><i class="bi bi-eye"></i></a>
                        <a href="./users/edit?uid=<?= $user['user_id'] ?>" class="btn btn-sm btn-light"><i class="bi bi-pencil"></i></a>
                    </td>
                    <td class="text-start">
                        <div class="d-flex align-items-center gap-2">
                            <img src="<?= DOMAIN ?>/upload/profile/default.png" class="img rounded-circle" width="30" height="30">
                            <div><?= $user['username'] ?></div>
                        </div>
                    </td>
                    <td class="text-start"><?= $user['firstname'] . ' ' . $user['lastname'] ?></td>
                    <td class="text-start"><?= $user['email'] ?></td>
                    <td class="text-start"><?= $user['contact'] ?></td>
                    <td class="text-start"><?= $user['role'] ?></td>
                    <td class="text-end"><?= FORMAT_DATE($user['created_at'], 'Y-m-d h:i A') ?></td>
                </tr>
            <?php } ?>

        </tbody>
    </table>
</div>