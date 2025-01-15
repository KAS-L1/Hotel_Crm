<?php
    $get_user_id = $_GET['uid'] ?? null;
    $user = $DB->SELECT_ONE_WHERE("users", "*", ["user_id" => $get_user_id]);
?>

<div class="d-flex justify-content-between flex-wrap mb-3">
    <h4 class="app__page-title"><i class="bi bi-person"></i> Information</h4>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?=DOMAIN?>/dashboard">Home</a></li>
            <li class="breadcrumb-item"><a href="<?=DOMAIN?>/users">Users</a></li>
            <li class="breadcrumb-item active" aria-current="page">Details</li>
        </ol>
    </nav>
</div>


    <div class="row">
        <div class="col-md-6 offset-md-3">
            
            <div class="card card-body">
                <div class="text-center py-4">
                    <img src="<?=DOMAIN?>/upload/profile/default.png" class="img rounded-circle thumb mb-2" width="125" height="125">
                    <div class="mt-3">
                        <div class="fs-4 fw-bold"><?=$user['firstname'].' '.$user['lastname']?></div>
                    </div>
                </div>
                <table class="table table-light">
                    <tr>
                        <td>User Role</td>
                        <td><span class="badge bg-secondary"><?=$user['role']?></span></td>
                    </tr>
                    <tr>
                        <td>User ID</td>
                        <td><?=$user['user_id']?></td>
                    </tr>
                    <tr>
                        <td>Username</td>
                        <td><?=$user['username']?></td>
                    </tr>
                    <tr>
                        <td>Email</td>
                        <td><?=$user['email']?></td>
                    </tr>
                    <tr>
                        <td>Contact</td>
                        <td><?=$user['contact']?></td>
                    </tr>
                    <tr>
                        <td>Created</td>
                        <td><?=DATE_SHORT_TIME($user['created'])?></td>
                    </tr>
                    <tr>
                        <td>Updated</td>
                        <td><?=$user['updated'] ?? '-'?></td>
                    </tr>
                </table>
                <div>
                    <a href="<?=DOMAIN?>/users/edit?uid=<?=$user['user_id']?>" class="btn app__btn-primary w-100">Edit Details</a>
                </div>
            </div>

        </div>
    </div>
