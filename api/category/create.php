<?php require("../../app/init.php") ?>
<?php require("../auth/auth.php") ?>

<?php

csrfProtect('verify');

if (isset($_POST['category'])) {

    $category = $DB->ESCAPE(VALID_STRING($_POST['category']));

    if (empty($category)) die(toast("error", "Field are required"));

    $productCategory = [
        "category_id" => GENERATE_ID('44', 4),
        "category" => $category,
        "created_at" => DATE_TIME,
    ];
    $insert_category = $DB->INSERT("product_categories", $productCategory);

    if (!$insert_category === "success") toast("error", "Category name failed to send");

    $notification_data = [
        "user_id" => AUTH_USER_ID,
        "message" => "Category has been created successfully",
        "action" => "CategoryCreation",
        "created_at" => DATE_TIME
    ];

    $DB->INSERT("notifications", $notification_data);
    
    toast("success", "Category sent successfully");
    die(refresh(2000));
} else {
    die(toast("error", "Request is invalid. Please try again later."));
}
