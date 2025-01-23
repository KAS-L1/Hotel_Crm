<?php require("../../app/init.php") ?>
<?php require("../auth/auth.php") ?>

<?php

if (isset($_POST['action'])) {
    
    $action = $_POST['action'] ?? null;
    $notification_id = $_POST['notif_id'] ?? null;

    switch ($action) {
        case 'mark_read':
            $DB->UPDATE('notifications', ['status' => 'Read'], ['id' => $notification_id]);
        break;
        case 'mark_all_read':
            $DB->UPDATE('notifications', ['status' => 'Read'], ['user_id' => AUTH_USER_ID, 'status' => 'Unread']);
        break;
        case 'delete':
            $DB->DELETE('notifications', ['id' => $notification_id]);
        break;
        case 'delete_all':
            $DB->DELETE('notifications', ['user_id' => AUTH_USER_ID]);
        break;
    }

}else{
    die(toast("error", "Invalid Request"));
}
