<?php

/**
 * USEFUL GLOBAL UTILITY FUNCTIONS
**/


// DEBUGGING
function pre($data){
    echo '<pre>';
    print_r($data);
}

function predie($data){
    echo '<pre>';
    die(print_r($data));
}

function ROUTE($path)
{
    return DOMAIN . "/" . $path;
}

// INPUT VALIDATION
function VALID_STRING($string){
    return strip_tags(preg_replace('/[^a-zA-Z0-9_@.]+/', ' ', trim($string)));
}

function VALID_NUMBER($int){
    return preg_replace('/[^0-9]/', '', $int);
}

function VALID_PASS($string){
    return trim($string);
}

function VALID_MAIL($email){
    return filter_var($email, FILTER_SANITIZE_EMAIL);
}

// CHARACTER FORMATING
function CHAR($string){
    return htmlspecialchars($string);
}

function LOWER($string){
    return strtolower($string);
}

function UPPER($string){
    return strtoupper($string);
}

function CAMEL($string){
    return ucwords($string);
}

function NOSPACE($string){
   return preg_replace('/[^A-Za-z0-9]+/', '', $string);
}

function SLUG($string){
   return  preg_replace('/[^A-Za-z0-9-]+/', '-', $string);
}


// NUMBER FORMATTING
function NUMBER($int, $decimal = 0){
    if($decimal == 0){
        return number_format($int);
    }else{
        return number_format($int, $decimal);
    }
}

function PESO($int, $decimal = 0){
    if($decimal == 0){
        return '₱'.number_format($int);
    }else{
        return '₱'.number_format($int, $decimal);
    }
}


// DATE FORMATTING
function FORMAT_DATE($date, $format){
    return date_format(date_create($date), $format);
}

function DATE_SHORT($date){
    return date_format(date_create($date),'M d, Y');
}

function DATE_TIME_SHORT($date){
    return date_format(date_create($date),'M d, Y h:i A');
}

function DATE_UPDATED($date){
    return $date != null ? date_format(date_create($date),'M d, Y') : null ;
}

function DATE_TIME_UPDATED($date){
    return $date != null ? date_format(date_create($date),'M d, Y h:i A') : null ;
}


// FILE UPLOAD SERVER
function UPLOAD_FILE($file_data, $file_path, $file_name, $file_extension){
    $file_name = $file_name.'.'.$file_extension;
    $source_path = $file_data['tmp_name'];
    $target_path = $file_path.'/'.$file_name;
    if(move_uploaded_file($source_path, $target_path)){
        return array('name' => $file_name, 'status' => 'success');
    }else{
        return array('name' => $file_name, 'status' => 'error');
    }
}


// OTHER FUNCTIONS
function RANDOM_STRING($length) {
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $characters_length = strlen($characters);
    $random_string = '';
    for ($i = 0; $i < $length; $i++) {
        $random_string .= $characters[random_int(0, $characters_length - 1)];
    }
    return $random_string;
}

function GENERATE_ID($prefix = null, $digits = 4)
{
    $digit = max(9999, pow(10, $digits) - 1);
    $random_digits = str_pad(rand(1000, $digit), $digits, "0", STR_PAD_LEFT);
    return $prefix . $random_digits;
}


function VALID_STRONG_PASS($password)
{
    $common_passwords = ['password123', '12345678', 'qwerty', 'letmein', '1234', 'welcome', 'admin', 'password1'];
    if (in_array(strtolower($password), $common_passwords)) {
        toast('error', 'Password is too common and easily guessable.');
        return false;
    }
    else if (strlen($password) < 8) {
        toast('error', 'Password should be at least 8 characters long.');
        return false;
    } else if (!preg_match('/[a-z]/', $password)) {
        toast('error', 'Password must contain at least one lowercase letter.');
        return false;
    } elseif (!preg_match('/[A-Z]/', $password)) {
        toast('error', 'Password must contain at least one uppercase letter.');
        return false;
    } elseif (!preg_match('/[0-9]/', $password)) {
        toast('error', 'Password must contain at least one number.');
        return false;
    } elseif (!preg_match('/[\W_]/', $password)) {
        toast('error', 'Password must contain at least one special character (e.g., !@#$%^&*).');
        return false;
    }else{
        return true;
    }
}

// CSRF Protection
function csrfProtect($action = 'generate')
{
    if (session_status() == PHP_SESSION_NONE) {
        session_start(); // Start the session if not already started
    }

    if ($action === 'generate') {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Generate CSRF token
        }
        // Return hidden input for the CSRF token
        return '<input type="hidden" id="csrf_token" name="csrf_token" value="' . htmlspecialchars($_SESSION['csrf_token']) . '">';
    }

    if ($action === 'verify') {
        if (empty($_SESSION['csrf_token']) || empty($_POST['csrf_token'])) {
            die(toast("error", "CSRF token not found"));
        }

        if (hash_equals($_SESSION['csrf_token'], $_POST['csrf_token']) === false) {
            die(toast("error", "Invalid CSRF token"));
        }
    }
}


function HASH_PASSWORD($password)
{
    return password_hash($password, PASSWORD_DEFAULT);
}

function VERIFY_PASSWORD($password, $hashed_password)
{
    return password_verify($password, $hashed_password);
}


