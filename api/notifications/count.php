<?php require("../../app/init.php") ?>
<?php require("../auth/auth.php") ?>

<?php

$notif = $DB->SELECT_ONE_WHERE('notifications', 'COUNT(id) count_notif', ["user_id" => AUTH_USER_ID, "status" => 'Unread'] );
$unread_count = $notif['count_notif'];

?>

<?php if ($unread_count > 0): ?>
    <span class="absolute top-0 flex h-5 w-5 items-center justify-center rounded-full bg-danger text-xs text-white ltr:right-0 rtl:left-0">
        <?= $unread_count ?>
    </span>
<?php endif; ?>