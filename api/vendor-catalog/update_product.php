<?php require("../../app/init.php"); ?>
<?php require("../auth/auth.php") ?>

<?php
csrfProtect('verify');

if (!isset($_POST['product_id'])) die(toast('error', 'Product ID is required'));

$requiredFields = ['product_id', 'name', 'category_id', 'unit_price', 'stock', 'description'];
foreach ($requiredFields as $field) {
    if (!isset($_POST[$field])) {
        die(toast('error', 'Invalid server request'));
    }
}
if (!isset($_SESSION['PRODUCT_THUMBNAIL']) || empty($_SESSION['PRODUCT_THUMBNAIL'])) {
    $data = [
        "name"        => $DB->ESCAPE(VALID_STRING(trim($_POST['name']))),
        "category_id" => $DB->ESCAPE(VALID_NUMBER($_POST['category_id'])),
        "status"  => $DB->ESCAPE(VALID_STRING($_POST['status'])),
        "unit_price"  => $DB->ESCAPE(VALID_NUMBER($_POST['unit_price'])),
        "stock"       => $DB->ESCAPE(VALID_NUMBER($_POST['stock'])),
        "description" => $DB->ESCAPE(VALID_STRING(trim($_POST['description']))),
    ];
} else {
    $data = [
        "name"        => $DB->ESCAPE(VALID_STRING(trim($_POST['name']))),
        "category_id" => $DB->ESCAPE(VALID_NUMBER($_POST['category_id'])),
        "status"  => $DB->ESCAPE(VALID_STRING($_POST['status'])),
        "unit_price"  => $DB->ESCAPE(VALID_NUMBER($_POST['unit_price'])),
        "stock"       => $DB->ESCAPE(VALID_NUMBER($_POST['stock'])),
        "description" => $DB->ESCAPE(VALID_STRING(trim($_POST['description']))),
        'image' => $_SESSION['PRODUCT_THUMBNAIL'],
    ];
}


$where = [
    "vendor_id" => AUTH_USER_ID,
    "product_id" => $DB->ESCAPE($_POST['product_id'])
];

$update_product = $DB->UPDATE('products', $data, $where);

if (!$update_product === "success") die(toast('error', $update_product['message']));

$notification_data = [
    "user_id" => AUTH_USER_ID,
    "message" => "Product has been updated: " . $data['name'],
    "action" => "ProductUpdate",
    "created_at" => DATE_TIME
];

$DB->INSERT("notifications", $notification_data);

toast('success', 'Product successfully updated');
unset($_SESSION['PRODUCT_THUMBNAIL']);
die(redirect('/vendor-catalog',2000));
