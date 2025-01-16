<?php
require("../../app/init.php");
require("jwt.php");

csrfProtect('verify');

// Initialize JWT
$jwt = new JWT("i-dont-have-yet-get-env"); // Use environment variable for the secret key.

$token = $_GET['token'] ?? null;

if (!$token) {
    die(toast("error", "Token is missing. Please request a new password reset link."));
}

// Verify the token
$payload = $jwt->verifyToken($token);

if (!$payload) {
    die(toast("error", "Invalid or expired token. Please request a new password reset link."));
}

// Handle form submission
    $token = $_POST['token'];
    $password = VALID_STRONG_PASS($_POST['password']);
    $confirm_password = VALID_STRONG_PASS($_POST['confirm_password']);

    if (empty($password) || empty($confirm_password)) {
        die(toast("error", "All fields are required."));
    }

    if ($password !== $confirm_password) {
        die(toast("error", "Passwords do not match. Please try again."));
    }

    // Hash and update password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    $user_id = $payload['user_id'];

    $update_data = ["password" => $hashed_password];
    $where = ["user_id" => $user_id];

    $result = $DB->UPDATE("users", $update_data, $where);

    if (!$result['success']) {
        die(toast("error", "Failed to update your password. Please try again later."));
    }

    unset($_SESSION['csrf_token']); // Clean up session data
    toast("success", "Your password has been successfully updated.");
    die(redirect("/login", 2000));

