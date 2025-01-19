<?php
    $get_user_id = $_GET['uid'] ?? null;
    $user = $DB->SELECT_ONE_WHERE("users", "*", ["user_id" => $get_user_id]);
?>

<div class="d-flex justify-content-between flex-wrap mb-3">
    <h4 class="app__page-title"><i class="bi bi-pencil-square"></i> Edit Details</h4>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?=DOMAIN?>/dashboard">Home</a></li>
            <li class="breadcrumb-item"><a href="<?=DOMAIN?>/users">Users</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit</li>
        </ol>
    </nav>
</div>

    <div class="row">
        <div class="col-md-6 offset-md-3">
            
            <div class="card card-body">
                <div class="text-center py-4">
                    <img src="<?=DOMAIN?>/upload/profile/default.png" class="img rounded-circle thumb mb-2" width="125" height="125">
                    <div class="mt-3">
                        <div class="fs-4 fw-bold"><?=$user['username']?></div>
                    </div>
                </div>
                <table class="table table-light">
                    <tr>
                        <td>First Name</td>
                        <td>
                            <input type="text" value="<?=$user['firstname']?>" class="form-control">
                        </td>
                    </tr>
                    <tr>
                        <td>Last Name</td>
                        <td>
                            <input type="text" value="<?=$user['lastname']?>" class="form-control">
                        </td>
                    </tr>
                    <tr>
                        <td>Email</td>
                        <td>
                            <input type="text" value="<?=$user['email']?>" class="form-control">
                        </td>
                    </tr>
                    <tr>
                        <td>Contact</td>
                        <td>
                            <input type="text" value="<?=$user['contact']?>" class="form-control">
                        </td>
                    </tr>
                    <tr>
                        <td>Role</td>
                        <td>
                            <select class="form-control">
                                <option selected disabled><?=$user['role']?></option>
                                <option>ADMIN</option>
                                <option>USER</option>
                            </select>
                        </td>
                    </tr>
                </table>
                <div>
                    <button type="submit" class="btn app__btn-primary w-100">Update</button>
                </div>
            </div>

        </div>
    </div>


