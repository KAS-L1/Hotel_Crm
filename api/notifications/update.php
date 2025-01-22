<?php
require("../../app/init.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = key($_POST);
    $notification_id = $_POST['notification_id'] ?? null;

    switch ($action) {
        case 'mark_read':
            if ($notification_id) {
                $DB->UPDATE('notifications', ['status' => 'Read'], ['id' => $notification_id]);
            }
            break;
        case 'mark_all_read':
            $DB->UPDATE('notifications', ['status' => 'Read'], ['user_id' => AUTH_USER_ID, 'status' => 'Unread']);
            break;
        case 'delete_notification':
            if ($notification_id) {
                $DB->DELETE('notifications', ['id' => $notification_id]);
            }
            break;
        case 'delete_all':
            $DB->DELETE('notifications', ['user_id' => AUTH_USER_ID]);
            break;
    }

    die(redirect($_SERVER['REQUEST_URI']));
}