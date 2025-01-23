<?php require("../../app/init.php") ?>
<?php require("../auth/auth.php") ?>

<?php

$notifications = $DB->SELECT_WHERE('notifications', '*', ["user_id" => AUTH_USER_ID], "ORDER BY created_at DESC");

?>

<?php if (empty($notifications)): ?>
    <li class="dark:text-white-light/90">
        <div class="p-4 text-center">
            No notifications found
        </div>
    </li>
<?php else: ?>
    <?php foreach ($notifications as $notification): ?>
        <li class="dark:text-white-light/90">
            <div class="group flex items-center px-4 py-2 <?= $notification['status'] === 'Unread' ? 'bg-blue-50/50 dark:bg-blue-900/20' : '' ?>">
                <div class="grid place-content-center rounded">
                    <div class="relative h-12 w-12">
                        <img class="h-12 w-12 rounded-full object-cover" src="<?= DOMAIN ?>/upload/profile/<?= AUTH_USER['picture'] ?>" alt="image">
                    </div>
                </div>
                <div class="flex flex-auto items-center justify-between ltr:pl-3 rtl:pr-3">
                    <div class="ltr:pr-3 rtl:pl-3">
                        <h6 class="<?= $notification['status'] === 'Unread' ? 'font-semibold' : '' ?>">
                            <?= $notification['message'] ?>
                        </h6>
                        <span class="block text-xs font-normal dark:text-gray-500">
                            <?= DATE_TIME_SHORT($notification['created_at']) ?>
                        </span>
                    </div>

                    <div class="flex space-x-2">
                        <?php if ($notification['status'] === 'Unread'): ?>
                            <button type="button" class="text-primary hover:text-primary-dark btnRead" data-notif_id="<?= $notification['id'] ?>" title="Mark as read">
                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                            </button>
                        <?php endif; ?>

                        <button type="button" class="text-danger hover:text-danger-dark btnDelete" data-notif_id="<?= $notification['id'] ?>" title="Delete">
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="3 6 5 6 21 6"></polyline>
                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                            </svg>
                        </button>

                    </div>
                </div>
            </div>
        </li>
    <?php endforeach; ?>
<?php endif; ?>


<script>
    $('.btnDelete').click(function() {
        const notif_id = $(this).data('notif_id');
        $.post("../api/notifications/action.php", {
            notif_id: notif_id,
            action: 'delete'
        }, function(res) {
            $('#responseNotif').html(res);
            loadNotifCount();
            loadNotifList();
        });
    });

    $('.btnRead').click(function() {
        const notif_id = $(this).data('notif_id');
        $.post("../api/notifications/action.php", {
            notif_id: notif_id,
            action: 'mark_read'
        }, function(res) {
            $('#responseNotif').html(res);
            loadNotifCount();
            loadNotifList();
        });
    });
</script>