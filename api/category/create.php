<?php require("../../app/init.php") ?>

<?php

csrfProtect('verify');

if(isset($_POST['category'])){

    $category = $DB->ESCAPE(VALID_STRING($_POST['category']));

    if(empty($category)) die(toast("error", "Field are required"));

    $productCategory = [
        "category_id" => GENERATE_ID('44', 4),
        "category" => $category,
        "created_at" => DATE_TIME,
    ];
    $insert_category = $DB->INSERT("product_categories", $productCategory);

    if (!$insert_category === "success") toast("error", "Category name failed to send");
    toast("success", "Category sent successfully");
    die(refresh(2000));

} else {
    die(toast("error", "Request is invalid. Please try again later."));
}