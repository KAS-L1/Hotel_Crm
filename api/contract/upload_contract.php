<?php
require("../../app/init.php");
require("../auth/auth.php");

csrfProtect('verify');

if (isset($_FILES['contract_file'])) {
    $contractId = $_POST['contract_id'];
    $contract = $DB->SELECT_ONE_WHERE('contracts', '*', ['contract_id' => $contractId]);

    // Handle file upload
    $file = $_FILES["contract_file"];
    $file_name = $contractId . '-' . $contract['vendor_id'];
    $file_extension = explode("/", $file["type"])[1];

    $upload_contract = UPLOAD_FILE($file, '../../upload/contract', $file_name, $file_extension);
    if ($upload_contract['status'] != 'success') {
        die(Toast("error", "Failed to upload contract file"));
    }

    // Update contract data
    $updateData = [
        'contract_file' => $upload_contract['name'],
        'status' => 'Pending Approval'
    ];

    if ($contract['is_expired'] == 1) {
        $updateData['renewal_status'] = 'Pending Renewal';
    }

    $updateContract = $DB->UPDATE('contracts', $updateData, ['contract_id' => $contractId]);

    // Notify vendor
    $DB->INSERT('notifications', [
        'user_id' => $contract['vendor_id'],
        'message' => "Contract document uploaded for your review",
        'action' => "/vendor-contract",
        'status' => 'Unread'
    ]);

    // Notify admin
    $DB->INSERT('notifications', [
        'user_id' => $contract['created_by'],
        'message' => "New contract document uploaded for #{$contractId}",
        'action' => "/contract",
        'status' => 'Unread'
    ]);

    toast('success', 'Contract uploaded successfully');
    die(refresh(2000));
}
// Modified approval section without authorization check
elseif (isset($_POST['approve_contract'])) {
    $contractId = $_POST['contract_id'];
    $contract = $DB->SELECT_ONE_WHERE('contracts', '*', ['contract_id' => $contractId]);

    $updateData = [
        'status' => 'Approved',
        'activated_at' => date('Y-m-d H:i:s'),
        'expiration_date' => date('Y-m-d', strtotime('+1 year')),
        'is_expired' => 0
    ];

    if ($contract['renewal_status'] === 'Pending Renewal') {
        $updateData['renewal_status'] = 'Renewed';
    }

    $DB->UPDATE('contracts', $updateData, ['contract_id' => $contractId]);

    // Notify vendor
    $DB->INSERT('notifications', [
        'user_id' => $contract['vendor_id'],
        'message' => "Contract #{$contractId} has been approved and activated",
        'action' => "/vendor-contract",
        'status' => 'Unread'
    ]);

    // Notify admin
    $DB->INSERT('notifications', [
        'user_id' => AUTH_USER_ID,
        'message' => "Contract #{$contractId} approved successfully",
        'action' => "/contract",
        'status' => 'Unread'
    ]);

    die(Toast("success", "Contract approved and activated!"));
} else {
    die(toast('error', 'Invalid request'));
}
