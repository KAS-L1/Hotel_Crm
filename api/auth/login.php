<?php require("../../app/init.php") ?>
<?php require("jwt.php") ?>

<?php

csrfProtect('verify');

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Helper function to store old input
function storeOldInput($key, $value)
{
    $_SESSION['old'][$key] = $value;
}

// Validate input
if (!isset($_POST['username']) || !isset($_POST['password'])) {
    die(toast("error", "Invalid server request"));
}

// Store old input
storeOldInput('username', $_POST['username']);

// Clean and validate input
$username = $DB->ESCAPE(VALID_STRING($_POST['username']));
$password = VALID_PASS($_POST['password']); // No need to escape since password is hashed.

if (empty($username) || empty($password)) {
    die(toast("error", "Username or password cannot be empty."));
}

// Fetch user from the database
$user = $DB->SELECT_ONE("users", "*", "WHERE username = '$username' OR email = '$username'");

if (empty($user)) {
    die(toast("error", "Invalid credentials")); // Generic message to prevent username enumeration.
}

// Verify password
if (!password_verify($password, $user['password'])) {
    die(toast("error", "Invalid credentials")); // Generic error message for security.
}

$expiry = isset($_POST['remember'])? strtotime('+1 month'): strtotime('+1 hour');
// Generate JWT
$jwt = new JWT('this-is-secure-secret-key');
$user_token = $jwt->createToken([
    'user_id'  => $user['user_id'],
    'username' => $user['username'],
    'email'    => $user['email'],
    'exp'      => $expiry  // Token expires in 1 hour
]);

// Handle "Remember Me" for cookie expiry
//$expiry = isset($_POST['remember']) ? strtotime('+1 month') : 0;

// Set cookie
if (!setcookie("_xsrf-token", $user_token, $expiry, "/")) {
    die(toast("error", "Failed to set cookie"));
}

// Clear old input data after successful login
unset($_SESSION['old']);

// Login successful
toast("success", "Login successful");
redirect("/dashboard");
