<?php require("../../app/init.php"); ?>
<?php require("../auth/auth.php"); ?>

<?php

csrfProtect('verify');

if (
    isset($_POST['name']) && isset($_POST['category_id']) && isset($_POST['unit_price']) &&
    isset($_POST['stock']) && isset($_POST['description'])
) {

    $data = [
        "product_id" => GENERATE_ID('22', 4),
        "name"        => $DB->ESCAPE(VALID_STRING($_POST['name'])),
        "category_id" => $DB->ESCAPE(VALID_NUMBER($_POST['category_id'])),
        "unit_price"  => $DB->ESCAPE(VALID_NUMBER($_POST['unit_price'])),
        "stock"       => $DB->ESCAPE(VALID_NUMBER($_POST['stock'])),
        "description" => $DB->ESCAPE(VALID_STRING(trim($_POST['description']))),
        "vendor_id"   => AUTH_USER_ID
    ];

    $insert_product = $DB->INSERT("products", $data);

    if (!$insert_product === "success") die(toast('error', 'Failed to create product'));
    
    $notification_data = [
        "user_id" => AUTH_USER_ID,
        "message" => "A new product has been added: " . $data['name'],
        "action" => "ProductCreation",
        "created_at" => DATE_TIME
    ];

    $notification_result = $DB->INSERT("notifications", $notification_data);

    toast('success', 'Product successfully created.');
    die(refresh(2000));
} else {
    die(toast('error', 'Invalid server request: Missing required fields.'));
}

?>