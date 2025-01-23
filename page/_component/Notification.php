<?php
// Get notifications with unread count
$notifications = $DB->SELECT_WHERE('notifications', '*', ["user_id" => AUTH_USER_ID], "ORDER BY created_at DESC");
$unread_count = count(array_filter($notifications, function ($n) {
    return $n['status'] === 'Unread';
}));

?>

<div class="dropdown" x-data="dropdown" @click.outside="open = false">
    <a href="javascript:;" class="relative block rounded-full bg-white-light/40 p-2 hover:bg-white-light/90 hover:text-primary dark:bg-dark/40 dark:hover:bg-dark/60" @click="toggle">
        <svg width="20" height="20" viewbox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M19.0001 9.7041V9C19.0001 5.13401 15.8661 2 12.0001 2C8.13407 2 5.00006 5.13401 5.00006 9V9.7041C5.00006 10.5491 4.74995 11.3752 4.28123 12.0783L3.13263 13.8012C2.08349 15.3749 2.88442 17.5139 4.70913 18.0116C9.48258 19.3134 14.5175 19.3134 19.291 18.0116C21.1157 17.5139 21.9166 15.3749 20.8675 13.8012L19.7189 12.0783C19.2502 11.3752 19.0001 10.5491 19.0001 9.7041Z" stroke="currentColor" stroke-width="1.5"></path>
            <path d="M7.5 19C8.15503 20.7478 9.92246 22 12 22C14.0775 22 15.845 20.7478 16.5 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
        </svg>

        <span id="loadNotifCount">
            <!-- Show Count -->
        </span>

    </a>

    <!-- Notification dropdown content -->
    <ul x-cloak x-show="open" x-transition x-transition.duration.300ms class="top-11 w-[300px] divide-y !py-0 text-dark ltr:-right-2 rtl:-left-2 dark:divide-white/10 dark:text-white-dark sm:w-[350px]">
        <li>
            <div class="flex items-center justify-between px-4 py-2 font-semibold hover:!bg-transparent">
                <h4 class="text-lg">Notifications</h4>
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
        </li>

        <div id="loadNotifications" class="notif__list-wrapper" style="overflow-y: scroll; max-height: 300px;">
            <!-- Show List -->
        </div>

        <div id="responseNotif"></div>
        <script>
            function loadNotifList() {
                $('#loadNotifications').load('../api/notifications/list.php');
            }

            function loadNotifCount() {
                $('#loadNotifCount').load('../api/notifications/count.php');
            }
            loadNotifCount();
            loadNotifList();

            $('.btnDeleteAll').click(function() {
                $.post("../api/notifications/action.php", {
                    action: 'delete_all'
                }, function(res) {
                    $('#responseNotif').html(res);
                    loadNotifCount();
                    loadNotifList();
                });
            });

            $('.btnReadAll').click(function() {
                $.post("../api/notifications/action.php", {
                    action: 'mark_all_read'
                }, function(res) {
                    $('#responseNotif').html(res);
                    loadNotifCount();
                    loadNotifList();
                });
            });
        </script>

        <li>
            <div class="p-4">
                <a href="/notifications" class="btn btn-primary btn-small block w-full text-center">
                    View All Notifications
                </a>
            </div>
        </li>
    </ul>
</div>