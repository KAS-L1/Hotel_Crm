<?php

$notifications = $DB->SELECT_WHERE('notifications', '*', ["user_id" => AUTH_USER_ID], "ORDER BY created_at DESC");

$unread_count = count(array_filter($notifications, function ($n) {
    return $n['status'] === 'Unread';
}));

?>

<style>
    li {
        list-style-type: none !important;
    }
</style>

<div class="page-content">
    <div class="flex justify-between">
        <div>
            <?php
            breadcrumb([
                ['label' => 'Home', 'url' => '/dashboard'],
                ['label' => 'Notifications', 'url' => '/notification'],
            ]);
            ?>
        </div>
        <div class="flex items-center justify-between px-4 py-2 font-semibold hover:!bg-transparent">
            <div class="flex space-x-2">
                <?php if ($unread_count > 0): ?>
                    <button type="button" class="text-xs text-primary hover:text-primary-dark btnReadAll">
                        Mark all read
                    </button>
                <?php endif; ?>

                <?php if (!empty($notifications)): ?>
                    <button type="button" class="text-xs text-danger hover:text-danger-dark btnDeleteAll">
                        Clear all
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>


    <div class="pt-5">
        <div class="relative flex h-full gap-5 sm:h-[calc(100vh_-_150px)]">


            <div class="panel h-full flex-1 overflow-auto p-0">
                <div class="flex h-full flex-col" style="overflow-y: scroll; max-height: 100vh;">
                    <div id="loadNotificationsAll"></div>
                </div>
            </div>

        </div>
    </div>

</div>

<div id="responseNotifAll"></div>

<script>
    function loadNotifListAll() {
        $.post("../api/notifications/list.php", {
            action: 'all_list'
        }, function(res) {
            $('#loadNotificationsAll').html(res);
            loadNotifCount();
            loadNotifList();
            loadNotifListAll();
        });
    }
   
    loadNotifListAll();

    $('.btnDeleteAll').click(function() {
        $.post("../api/notifications/action.php", {
            action: 'delete_all'
        }, function(res) {
            $('#responseNotifAll').html(res);
            loadNotifCount();
            loadNotifList();
            loadNotifListAll();
        });
    });

    $('.btnReadAll').click(function() {
        $.post("../api/notifications/action.php", {
            action: 'mark_all_read'
        }, function(res) {
            $('#responseNotifAll').html(res);
            loadNotifCount();
            loadNotifList();
            loadNotifListAll();
        });
    });
</script>