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

function PRIZE($int, $decimal = 0){
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
function UPLOAD_FILE($file_data, $file_path, $file_name, $file_extension = 'jpg'){
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



