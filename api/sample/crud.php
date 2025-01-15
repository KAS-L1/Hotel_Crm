<?php require("../../app/init.php") ?>

<?php
/**
 * SAMPLE CRUD SQL COMMAND
**/


// CREATE DATA
$data = [
    "user_id" => rand(100000, 999999),
    "username" => 'user'.rand(1000, 9999),
    "password" => md5('123456'),
    "firstname" => 'Jhon',
    "lastname" => 'Doe',
    "email" => 'jhondoe'.rand(100, 999).'@mail.com',
    "contact" => '0901'.rand(1000000, 9999999),
    "created" => DATE_TIME
];
$user_insert = $DB->INSERT("users", $data);
if (!$user_insert['success']) die($user_insert['message']); // validate query
// If query is successful
pre($user_insert['message']);
pre($data);


// READ ALL DATA
$users = $DB->SELECT("users", "*", "ORDER BY ID DESC");
pre($users);

// READ SINGLE DATA
$user = $DB->SELECT_ONE_WHERE("users", "*", ["username" => 'webmaster']);
pre($user);


// UPDATE DATA
$data = [
    "firstname" => 'Web3',
    "lastname" => 'Master3',
    "email" => 'webmaster3@mail.com',
    "updated" => DATE_TIME
];
$user_update = $DB->UPDATE("users", $data, ["user_id" => '123456']);
if (!$user_update['success']) die($user_update['message']); // validate query
// If query is successful
pre($user_update['message']);


// DELETE DATA
$user_delete = $DB->DELETE("users", ["user_id" => '368766']);
if (!$user_delete['success']) die($user_delete['message']); // validate query
// If query is successful
pre($user_delete['message']);






