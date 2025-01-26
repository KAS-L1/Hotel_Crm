<?php

function GetProducts($order_by = 'ASC'){
    global $DB;
    $products = $DB->SELECT_JOIN(
        ['products', 'product_categories'],
        '*',
        [
            [['t1.category_id', 't2.category_id']]
        ],
        ['INNER JOIN'],
        [],
        'ORDER BY t1.id '.$order_by
    );
    return $products;
}

function GetVendorProducts($vendor, $order_by = 'ASC')
{
    global $DB;
    $products = $DB->SELECT_JOIN(
        ['products', 'product_categories', 'users'],
        't1.*, t2.category, t3.username, t3.company, t3.first_name, t3.last_name, t3.email, t3.picture',
        [
            [['t1.category_id', 't2.category_id']],
            [['t1.vendor_id', 't3.user_id']]
        ],
        ['INNER JOIN', 'INNER JOIN'],
        ['t1.vendor_id' => $vendor],
        'ORDER BY t1.id '.$order_by
    );
    return $products;
}
