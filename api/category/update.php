<?php require("../../app/init.php"); ?>
<?php require("../auth/auth.php") ?>

<?php

csrfProtect('verify');

if (isset($_POST['category']) && isset($_POST['category_id'])) {
    $category = $DB->ESCAPE(VALID_STRING($_POST['category']));
    $categoryId = $DB->ESCAPE($_POST['category_id']);

    if (empty($category)) die(toast("error", "Category name is required"));

    $data = [
        "category" => $category,
        "updated_at" => DATE_TIME
    ];

    $where = ['id' => $categoryId];
    $update_category = $DB->UPDATE('product_categories', $data, $where);

    if (!$update_category === 'success') die(toast("error", "Failed to update category"));

    $notification_data = [
        "user_id" => AUTH_USER_ID,
        "message" => "Category has been update successfully",
        "action" => "CategoryUpdate",
        "created_at" => DATE_TIME
    ];

    $DB->INSERT("notifications", $notification_data);

    toast("success", "Category updated successfully");
    die(refresh(2000));
} else {
    die(toast("error", "Invalid request"));
}
