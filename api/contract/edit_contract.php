<?php require "../../app/init.php"; ?>
<?php require "../auth/auth.php"; ?>

<?php

csrfProtect('verify');

if (isset($_POST['contract_id']) && isset($_POST['editContractExpirationDate'])) {

    $contract_id = $_POST['contract_id'];
    $editContractExpirationDate = $_POST['editContractExpirationDate'];

    $contract = $DB->SELECT_ONE_WHERE('contracts', '*', ['id' => $contract_id]);

    if ($contract) {
        $data = [
            "renewal_status" => (strtotime($editContractExpirationDate) < strtotime(date('Y-m-d'))) ? 'Expired' : 'Pending',
            "expiration_date" => $editContractExpirationDate,
            "is_expired" => (strtotime($editContractExpirationDate) < strtotime(date('Y-m-d'))) ? 1 : 0,
        ];

        $update = $DB->UPDATE('contracts', $data, ['id' => $contract_id]);

        if (!$update['success']) die(toast('error', 'Contract expiration date not updated'));


        // Notify vendor about the contract update
        $DB->INSERT('notifications', [
            'user_id' => $contract['vendor_id'],
            'message' => "Contract #{$contract_id} has been updated with a new expiration date",
            'action' => "/vendor-contract",
            'status' => 'Unread'
        ]);

        // Notify vendor if the contract is expired
        if (strtotime($editContractExpirationDate) < strtotime(date('Y-m-d'))) {
            $DB->INSERT('notifications', [
                'user_id' => $contract['vendor_id'],
                'message' => "Contract #{$contract_id} has expired",
                'action' => "/vendor-contract",
                'status' => 'Unread'
            ]);
        }

        // Notify the user who updated the expiration date
        $DB->INSERT('notifications', [
            'user_id' => AUTH_USER_ID, // Assuming the user ID is stored in the session
            'message' => "You have updated the expiration date for Contract #{$contract_id}",
            'action' => "/contract",
            'status' => 'Unread'
        ]);

        toast('success', 'Contract expiration date updated successfully');
        die(refresh(2000));
    } else {
        die(toast('error', 'Contract not found'));
    }
}
