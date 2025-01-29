<?php
function GetProducts($order_by = 'ASC')
{
    global $DB;
    $allowed_orders = ['ASC', 'DESC'];
    $order = in_array(strtoupper($order_by), $allowed_orders) ? strtoupper($order_by) : 'ASC';

    return $DB->SELECT_JOIN(
        ['products', 'product_categories'],
        't1.*, t2.category',
        [
            [['t1.category_id', 't2.category_id']]
        ],
        ['INNER JOIN'],
        [],
        'ORDER BY t1.id ' . $order
    );
}

function GetSingleProduct($product_id)
{
    global $DB;
    $product_id = intval($product_id); // Ensure integer

    $products = $DB->SELECT_JOIN(
        ['products', 'product_categories'],
        't1.*, t2.category',
        [
            [['t1.category_id', 't2.category_id']]
        ],
        ['INNER JOIN'],
        ['t1.product_id' => $product_id],
        'LIMIT 1'
    );

    return !empty($products) ? $products[0] : null;
}

function GetVendorProducts($vendor, $order_by = 'ASC')
{
    global $DB;
    $vendor = intval($vendor);
    $allowed_orders = ['ASC', 'DESC'];
    $order = in_array(strtoupper($order_by), $allowed_orders) ? strtoupper($order_by) : 'ASC';

    return $DB->SELECT_JOIN(
        ['products', 'product_categories', 'users'],
        't1.*, t2.category, t3.username, t3.company, t3.first_name, t3.last_name, t3.email, t3.picture',
        [
            [['t1.category_id', 't2.category_id']],
            [['t1.vendor_id', 't3.user_id']]
        ],
        ['INNER JOIN', 'INNER JOIN'],
        ['t1.vendor_id' => $vendor],
        'ORDER BY t1.id ' . $order
    );
}

function getCategoryOptions()
{
    global $DB;
    $categories = $DB->SELECT(
        'product_categories',
        'category_id, category',
        'ORDER BY category ASC'
    );

    return array_column($categories, 'category', 'category_id');
}
