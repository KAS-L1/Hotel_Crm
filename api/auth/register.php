<?php require("../../app/init.php") ?>
<?php

csrfProtect('verify'); // Protect against CSRF attacks

if(isset($_POST['first_name'], $_POST['last_name'], $_POST['email'], $_POST['password'])){

    // Set data on session
    $_SESSION['old'] = $_POST;

    // Retrieve and sanitize input data
    $first_name = $DB->ESCAPE(VALID_STRING($_POST['first_name']));
    $last_name = $DB->ESCAPE(VALID_STRING($_POST['last_name']));
    $address = $DB->ESCAPE(VALID_STRING($_POST['address']));
    $contact = $DB->ESCAPE(VALID_NUMBER($_POST['contact']));
    $email = $DB->ESCAPE(VALID_MAIL($_POST['email']));
    $username = $DB->ESCAPE(VALID_STRING($_POST['username']));
    $company = $DB->ESCAPE(VALID_STRING($_POST['company']));
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validate inputs
    if (empty($first_name) || empty($last_name) || empty($contact)|| empty($address) || empty($email) || empty($username)|| empty($company) || empty($password)) {
        die(toast("error", "All fields are required"));
    }

    // Handle strong password validation
    if (!VALID_STRONG_PASS($_POST['password'])) {
        die();
    }

    if ($password !== $confirm_password) {
        die(toast("error", "Passwords do not match."));
    }
    

    // Check if the email or username is already registered
    $userExists = $DB->SELECT_ONE_WHERE("users", "*", ["email" => $email]);
    if (!empty($userExists)) {
        die(toast("error", "Email already registered"));
    }

    $usernameExists = $DB->SELECT_ONE_WHERE("users", "*", ["username" => $username]);
    if (!empty($usernameExists)) {
        die(toast("error", "Username is already taken"));
    }

    // Hash the password
    $hashed_password = HASH_PASSWORD($password);

    // Insert new user into the database
    $userData = [
        "user_id" => GENERATE_ID('11', 4),
        "first_name" => $first_name,
        "last_name" => $last_name,
        "contact" => $contact,
        "address" => $address,
        "username" => $username,
        "company" => $company,
        "password" => $hashed_password,
        "email" => $email,
        "role" => 'Vendor',
        "status" => 'Pending',
        "created_at" => DATE_TIME,
    ];
    $insert_user = $DB->INSERT("users", $userData);

    if ($insert_user['success']) {

        toast("success", "Registration successful! Please check your email to verify your account.");
        unset($_SESSION['old']);
        die(redirect("/login", 2000));

    } else {
        die(toast("error", "Registration failed. Please try again later."));
    }

}else{
    die(toast("error", "Request is invalid. Please try again later."));
}