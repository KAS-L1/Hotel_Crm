<?php require("../../app/init.php") ?>
<?php require("../auth/auth.php") ?>

<?php

csrfProtect('verify');
function is_valid_file_size($file, $max_size)
{
    return $file['size'] <= $max_size;
}

// Function to get file extension based on MIME type
function get_file_extension($mime_type)
{
    $mime_types = [

        'application/pdf' => 'pdf',
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/gif' => 'gif',
        'application/msword' => 'doc',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
        'application/vnd.ms-excel' => 'xls',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'xlsx',
        'application/vnd.ms-powerpoint' => 'ppt',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'pptx',
        
        // Add other MIME types as needed
    ];

    return isset($mime_types[$mime_type]) ? $mime_types[$mime_type] : null;
}

// Max file size (10MB)
$max_file_size = 10 * 1024 * 1024; // 10MB

// Validate Data
if (isset($_FILES['business_license'], $_FILES['tin_certificate'], $_FILES['certificate'])) {

    // Check if application already exists
    $application_exist = $DB->SELECT_ONE_WHERE("vendors_application", "*", ["vendor_id" => AUTH_USER_ID]);
    if (!empty($application_exist)) die(toast("error", "Application already submitted"));

    // Upload business license file
    $file_license_extension = get_file_extension($_FILES['business_license']['type']);
    if (!$file_license_extension) die(toast("error", "Unsupported business license file type"));
    if (!is_valid_file_size($_FILES['business_license'], $max_file_size)) {
        die(toast("error", "Business license file is too large"));
    }
    $file_license = UPLOAD_FILE($_FILES['business_license'], '../../upload/document', AUTH_USER_ID . '-' . uniqid() . '-business', $file_license_extension);
    if ($file_license['status'] != 'success') die(toast("error", "Failed to upload business license"));

    // Upload tin certificate file
    $file_tin_extension = get_file_extension($_FILES['tin_certificate']['type']);
    if (!$file_tin_extension) die(toast("error", "Unsupported TIN certificate file type"));
    if (!is_valid_file_size($_FILES['tin_certificate'], $max_file_size)) {
        die(toast("error", "TIN certificate file is too large"));
    }
    $file_tin = UPLOAD_FILE($_FILES['tin_certificate'], '../../upload/document', AUTH_USER_ID . '-' . uniqid() . '-tin', $file_tin_extension);
    if ($file_tin['status'] != 'success') die(toast("error", "Failed to upload TIN certificate"));

    // Upload certificate file
    $file_certificate_extension = get_file_extension($_FILES['certificate']['type']);
    if (!$file_certificate_extension) die(toast("error", "Unsupported certificate file type"));
    if (!is_valid_file_size($_FILES['certificate'], $max_file_size)) {
        die(toast("error", "Certificate file is too large"));
    }
    $file_certificate = UPLOAD_FILE($_FILES['certificate'], '../../upload/document', AUTH_USER_ID . '-' . uniqid() . '-certificate', $file_certificate_extension);
    if ($file_certificate['status'] != 'success') die(toast("error", "Failed to upload certificate"));

    $file_references = null;
    if (isset($_FILES['other_references']) && $_FILES['other_references']['error'] == 0) {
        // Validate other references file size and extension
        $file_references_extension = get_file_extension($_FILES['other_references']['type']);
        if (!$file_references_extension) die(toast("error", "Unsupported other references file type"));
        if (!is_valid_file_size($_FILES['other_references'], $max_file_size)) {
            die(toast("error", "Other references file is too large"));
        }

        $file_references = UPLOAD_FILE($_FILES['other_references'], '../../upload/document', AUTH_USER_ID . '-' . uniqid() . '-references', $file_references_extension);
        if ($file_references['status'] != 'success') die(toast("error", "Failed to upload other references"));
    }

    // Prepare the application data
    $applicationData = [
        "vendor_id" => AUTH_USER_ID,
        "business_license" => $file_license['name'],
        "tin_certificate" => $file_tin['name'],
        "certificate" => $file_certificate['name'],
        "other_references" => $file_references ? $file_references['name'] : null,
        "created_at" => DATE_TIME,
    ];

    pre($applicationData);

    // Insert application into database
    $insert_application = $DB->INSERT("vendors_application", $applicationData);
    if (!$insert_application == "success") die(toast("error", "Failed to insert application"));

    // Success message
    toast("success", "Application has been submitted.");
    die(refresh(2000));
}


