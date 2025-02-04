<?php require("../../app/init.php") ?>

<?php
csrfProtect('verify');

if (isset($_FILES['contract_file'])) {
    $contractId = $_POST['contract_id'];
    $contract = $DB->SELECT_ONE_WHERE('contracts', '*', ['contract_id' => $contractId]);

    $file = $_FILES["contract_file"];
    $file_name = $contractId . '-signed-' . $contract['vendor_id'];
    $file_extension = explode("/", $file["type"])[1];

    $upload_contract = UPLOAD_FILE($file, '../../upload/contract', $file_name, $file_extension);
    if ($upload_contract['status'] != 'success') {
        die(Toast("error", "Failed to upload signed contract file"));
    }

    // Update contract status as signed
    $updateData = [
        'contract_file' => $upload_contract['name'],
        'is_signed' => 1,
        'activated_at' => date('Y-m-d H:i:s')
    ];

    // If this is a renewal (contract was expired)
    if ($contract['is_expired'] == 1) {
        $updateData['is_expired'] = 0;
        $updateData['renewal_status'] = 'Renewed';
        $updateData['expiration_date'] = date('Y-m-d', strtotime('+1 year'));
    }

    $updateContract = $DB->UPDATE('contracts', $updateData, ['contract_id' => $contractId]);

    // Notify admin
    $DB->INSERT('notifications', [
        'user_id' => $contract['created_by'], // Notify the admin who created the contract
        'message' => $contract['is_expired'] == 1 ?
            "Vendor has uploaded renewed contract for {$contractId}" :
            "Vendor has uploaded signed contract for {$contractId}",
        'action' => "/admin/contracts",
        'status' => 'Unread'
    ]);

    // Add after successful file upload
    $DB->UPDATE('contracts', ['status' => 'Pending Approval'], ['contract_id' => $contractId]);

    // Update notification to admin
    $DB->INSERT('notifications', [
        'user_id' => $contract['created_by'],
        'message' => "Vendor has signed contract #{$contractId} - awaiting approval",
        'action' => "/contract",
        'status' => 'Unread'
    ]);

    toast('success', 'Contract uploaded successfully');
    die(refresh(2000));
} else {
    die(toast('error', 'Select a file to upload'));
}
