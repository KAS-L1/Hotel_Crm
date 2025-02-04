<?php require("../../app/init.php"); ?>
<?php require("../auth/auth.php"); ?>

<?php


if (!isset($_POST['product_id']) || !is_numeric($_POST['product_id'])) {
    die(toast("error", "Invalid request"));
}

$product_id = $DB->ESCAPE($_POST['product_id']);

// Fetch the product first
$product = $DB->SELECT_ONE_WHERE('products', '*', ['product_id' => $product_id]);

if (!$product) die(toast("error", "Product not found"));

// Attempt to delete product
$delete_product = $DB->DELETE('products', ['product_id' => $product_id]);

if (!$delete_product === "success") die(toast("error", "Failed to delete product"));

$product_name = isset($product['name']) ? htmlspecialchars($product['name']) : 'Unknown Product';

// Insert deletion notification
$notification_data = [
    "user_id" => AUTH_USER_ID,
    "message" => "A product has been deleted: " . $product_name,
    "action" => "ProductDeletion",
    "created_at" => DATE_TIME
];

$DB->INSERT("notifications", $notification_data);

toast('success', 'Product successfully deleted.');
die(refresh(2000));

?>
