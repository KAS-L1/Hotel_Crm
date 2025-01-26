<?php
require("../../app/init.php");
require("../auth/auth.php");

csrfProtect('verify');

function is_valid_file_size($file, $max_size)
{
    return $file['size'] <= $max_size;
}

function get_file_extension($mime_type)
{
    $allowed_mime_types = [
        'application/pdf' => 'pdf',
        'application/msword' => 'doc',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
        'application/vnd.ms-excel' => 'xls',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'xlsx',
        'text/csv' => 'csv',
    ];

    return isset($allowed_mime_types[$mime_type]) ? $allowed_mime_types[$mime_type] : null;
}

$max_file_size = 10 * 1024 * 1024; // 10MB
$allowed_file_types = "Allowed file types: PDF (.pdf), Word (.doc, .docx), Excel (.xls, .xlsx), CSV (.csv)";

$required_files = [
    'business_license' => 'Business license',
    'tin_certificate' => 'TIN certificate',
    'certificate' => 'Certificate',
    'other_references' => 'Other references'
];

foreach ($required_files as $file_key => $file_name) {
    if (!isset($_FILES[$file_key]) || $_FILES[$file_key]['error'] === UPLOAD_ERR_NO_FILE) {
        die(toast("error", "$file_name file is required. $allowed_file_types"));
    }

    $file_extension = get_file_extension($_FILES[$file_key]['type']);
    if (!$file_extension) {
        die(toast("error", "Unsupported file type for $file_name. $allowed_file_types"));
    }

    if (!is_valid_file_size($_FILES[$file_key], $max_file_size)) {
        die(toast("error", "$file_name file is too large (max 10MB)"));
    }
}

if (isset($_FILES['business_license'], $_FILES['tin_certificate'], $_FILES['certificate'], $_FILES['other_references'])) {
    $application_exist = $DB->SELECT_ONE_WHERE("vendors_application", "*", ["vendor_id" => AUTH_USER_ID]);
    if (!empty($application_exist)) die(toast("error", "Application already submitted"));

    $file_license = UPLOAD_FILE($_FILES['business_license'], '../../upload/document', AUTH_USER_ID . '-' . uniqid() . '-business', get_file_extension($_FILES['business_license']['type']));
    if ($file_license['status'] != 'success') die(toast("error", "Failed to upload business license"));

    $file_tin = UPLOAD_FILE($_FILES['tin_certificate'], '../../upload/document', AUTH_USER_ID . '-' . uniqid() . '-tin', get_file_extension($_FILES['tin_certificate']['type']));
    if ($file_tin['status'] != 'success') die(toast("error", "Failed to upload TIN certificate"));

    $file_certificate = UPLOAD_FILE($_FILES['certificate'], '../../upload/document', AUTH_USER_ID . '-' . uniqid() . '-certificate', get_file_extension($_FILES['certificate']['type']));
    if ($file_certificate['status'] != 'success') die(toast("error", "Failed to upload certificate"));

    $file_references = UPLOAD_FILE($_FILES['other_references'], '../../upload/document', AUTH_USER_ID . '-' . uniqid() . '-references', get_file_extension($_FILES['other_references']['type']));
    if ($file_references['status'] != 'success') die(toast("error", "Failed to upload other references"));

    $applicationData = [
        "vendor_id" => AUTH_USER_ID,
        "business_license" => $file_license['name'],
        "tin_certificate" => $file_tin['name'],
        "certificate" => $file_certificate['name'],
        "other_references" => $file_references['name'],
        "created_at" => DATE_TIME,
    ];

    $insert_application = $DB->INSERT("vendors_application", $applicationData);
    if (!$insert_application == "success") die(toast("error", "Failed to insert application"));

    toast("success", "Application has been submitted.");
    die(refresh(2000));
}
 