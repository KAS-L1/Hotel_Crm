<?php require("../../app/init.php") ?>

<?php

csrfProtect('verify');

if(isset($_FILES['contract_file'])){

    $contractId = $_POST['contract_id'];
    $file = $_FILES["contract_file"];
    $file_name = $file["name"];
    $file_extension = explode("/",$file["type"])[1];
    
    $upload_contract = UPLOAD_FILE($file, '../../upload/contract', $file_name, $file_extension);
    if ($upload_contract['status'] != 'success') die(Toast("error", "Failed to upload contract file"));  

    toast('success', 'Contract uploaded successfully '. $file_name);
    // $updateContract = $DB->UPDATE('contracts', ['contract_file' => $upload_contract['name']], ['contract_id' => $_POST['contract_id']]);
    $contract = $DB->SELECT_ONE_WHERE('contracts', 'vendor_id', ['contract_id' => $contractId]);
    $DB->INSERT('notifications', [
        'user_id' => $contract['vendor_id'],
        'message' => "Contract document uploaded for your review",
        'action' => "/vendor-contract",
        'status' => 'Unread'
    ]);
    toast('success', 'Contract uploaded and vendor notified');
    die(refresh(2000));
}else{
    die(toast('error', 'Select a file to upload'));
}