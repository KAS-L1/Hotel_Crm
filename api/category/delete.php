<?php require("../../app/init.php"); ?>
<?php require("../auth/auth.php") ?>

<?php

csrfProtect('verify');

if (!isset($_POST['category_id']) || !isset($_POST['csrf_token'])) {
    die(toast("error", "Invalid request"));
}

$categoryId = $DB->ESCAPE($_POST['category_id']);
$where = ['id' => $categoryId];

$category = $DB->SELECT_ONE_WHERE('product_categories', 'id', $where);
if (!$category) die(toast("error", "Category not found"));


$delete_result = $DB->DELETE('product_categories', $where);
if (!$delete_result === 'success') die(toast("error", "Failed to delete category"));

$notification_data = [
    "user_id" => AUTH_USER_ID,
    "message" => "Category has been deleted successfully",
    "action" => "CategoryDeletion",
    "created_at" => DATE_TIME
];

$DB->INSERT("notifications", $notification_data);

toast("success", "Category deleted successfully");
die(refresh(2000));
