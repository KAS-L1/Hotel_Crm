<?php
require("../../app/init.php");
require("jwt.php");

csrfProtect('verify');

// Initialize JWT
$jwt = new JWT("this-is-my-password-token-recovery"); // Use environment variable for the secret key.

if(!isset($_POST['token'])) die(toast("error", "Token recovery is invalid"));
if (!isset($_POST['password'])) die(toast("error", "Password is required for recovery"));

$token = $DB->ESCAPE($_POST['token'] ?? null);

// Verify the token
$payload = $jwt->verifyToken($token);
if (!$payload) {
    die(toast("error", "Invalid or expired token. Please request a new password reset link."));
}

// Handle strong password validation
if (!VALID_STRONG_PASS($_POST['password'])) {
    die();
}

$password = $_POST['password'] ?? null;
$confirm_password = $_POST['confirm_password'];

if (empty($password) || empty($confirm_password)) {
    die(toast("error", "All fields are required"));
}

if ($password !== $confirm_password) {
    die(toast("error", "Passwords do not match. Please try again."));
}

// Hash and update password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);
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

