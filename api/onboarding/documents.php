<?php require("../../app/init.php") ?>
<?php require("../auth/auth.php") ?>

<?php

pre($_FILES);
// Validate Data
if (isset($_FILES['business_license'], $_FILES['tin_certificate'], $_FILES['certificate'])) {

    $application_exist = $DB->SELECT_ONE_WHERE("vendors_application", "*", ["vendor_id" => AUTH_USER_ID]);
    if (!empty($application_exist)) die(toast("error", "Application already submitted"));

    $file_license = UPLOAD_FILE($_FILES['business_license'], '../../upload/document', AUTH_USER_ID . '-' . uniqid() . '-business', explode('/', $_FILES['business_license']['type'])[1]);
    if ($file_license['status'] != 'success') die(toast("error", "Failed to upload business license"));

    $file_tin = UPLOAD_FILE($_FILES['tin_certificate'], '../../upload/document', AUTH_USER_ID . '-' . uniqid() . '-tin', explode('/', $_FILES['tin_certificate']['type'])[1]);
    if ($file_tin['status'] != 'success') die(toast("error", "Failed to upload tin certificate"));

    $file_certificate = UPLOAD_FILE($_FILES['certificate'], '../../upload/document', AUTH_USER_ID . '-' . uniqid() . '-certificate', explode('/', $_FILES['certificate']['type'])[1]);
    if ($file_certificate['status'] != 'success') die(toast("error", "Failed to upload certificate"));

    $file_references = null;
    if (isset($_FILES['other_references']) && $_FILES['other_references']['error'] == 0) {
        $file_references = UPLOAD_FILE($_FILES['other_references'], '../../upload/document', AUTH_USER_ID . '-' . uniqid() . '-references', explode('/', $_FILES['other_references']['type'])[1]);
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

    // Insert application into database
    $insert_application = $DB->INSERT("vendors_application", $applicationData);
    if (!$insert_application == "success") die(toast("error", "Failed to insert application"));

    toast("success", "Application has been submitted.");
    die(refresh(2000));
}
?>
