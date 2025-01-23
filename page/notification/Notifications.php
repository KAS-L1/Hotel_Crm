<?php

// Fetch paginated notifications
$notifications = $DB->SELECT_WHERE('notifications', '*', ["user_id" => AUTH_USER_ID], "ORDER BY created_at DESC");

?>

<div class="page-content">
    <?php
    breadcrumb([
        ['label' => 'Home', 'url' => '/dashboard'],
        ['label' => 'Notifications', 'url' => '/notification'],
    ]);
    ?>
    <div class="pt-5">
        <div class="relative flex h-full gap-5 sm:h-[calc(100vh_-_150px)]">


            <div class="panel h-full flex-1 overflow-auto p-0">
                <div class="flex h-full flex-col">

                    <div class="table-responsive min-h-[400px] grow overflow-y-auto sm:min-h-[300px]">
                        <table class="table-hover">
                            <tbody>

                                <?php foreach ($notifications as $notification): ?>
                                    <tr>
                                        <td>
                                            <div class="whitespace-nowrap text-base font-semibold group-hover:text-primary"><?= $notification['action'] ?></div>
                                            <div class="min-w-[300px] overflow-hidden text-white-dark line-clamp-1"><?= $notification['message'] ?></div>
                                        </td>
                                        <td class="w-1">
                                            <p class="whitespace-nowrap font-medium text-white-dark text-xs"><?= DATE_TIME_SHORT($notification['created_at']) ?></p>
                                        </td>
                                        <td class="w-1">
                                            Action
                                        </td>
                                    </tr>
                                <?php endforeach ?>

                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

        </div>
    </div>

</div>