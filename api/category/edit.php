<?php require("../../app/init.php") ?>

<?php

csrfProtect('verify');

if (isset($_POST['category_id'])) {
    $categoryId = $DB->ESCAPE($_POST['category_id']);

    $where = ['id' => $categoryId];
    $category = $DB->SELECT_ONE_WHERE('product_categories', '*', $where);

    if ($category) {
        echo json_encode([
            'status' => 'success',
            'category' => $category
        ]);
        exit;
    }

    echo json_encode([
        'status' => 'error',
        'message' => 'Category not found'
    ]);
    exit;
}

echo json_encode([
    'status' => 'error',
    'message' => 'Invalid request'
]);
