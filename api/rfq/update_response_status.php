<?php
require("../../app/init.php");
require("../auth/auth.php");

if (isset($_POST['response_id']) && isset($_POST['status'])) {
    $response_id = $DB->ESCAPE($_POST['response_id']);
    $status = $DB->ESCAPE($_POST['status']);

   
        // Validate status
        if (!in_array($status, ['Accepted', 'Rejected'])) {
            throw new Exception("Invalid status specified");
        }

        // Get RFQ response details with locking
        $response = $DB->SELECT_ONE_WHERE(
            "rfq_responses",
            "rfq_id, vendor_id",
            ["response_id" => $response_id]);

        if (!$response) {
            throw new Exception("RFQ response not found");
        }

        // Get RFQ details
        $rfq = $DB->SELECT_ONE_WHERE(
            "rfq_requests",
            "product_name, created_by, delivery_date",
            ["rfq_id" => $response['rfq_id']]
        );
        if (!$rfq) {
            throw new Exception("RFQ not found");
        }

        // Update the response status
        $update_response = $DB->UPDATE("rfq_responses", [
            "status" => $status,
            "updated_at" => date('Y-m-d H:i:s')
        ], ["response_id" => $response_id]);

        if (!$update_response['success']) {
            throw new Exception("Failed to update RFQ response");
        }

        if ($status === 'Accepted') {
            // Update RFQ status and reject other responses
            $update_rfq = $DB->UPDATE("rfq_requests", [
                "status" => "Awarded",
                "updated_at" => date('Y-m-d H-i-s')
            ], ["rfq_id" => $response['rfq_id']]);

            if (!$update_rfq['success']) {
                throw new Exception("Failed to update RFQ status");
            }

            // Reject other responses
            $reject_others = $DB->UPDATE("rfq_responses", [
                "status" => "Rejected",
                "updated_at" => date('Y-m-d H:i:s')
            ], [
                "rfq_id" => $response['rfq_id'],
                "response_id !=" => $response_id
            ]);

            if (!$reject_others['success']) {
                throw new Exception("Failed to reject other responses");
            }

            // Notify other vendors
            $other_vendors = $DB->SELECT_WHERE(
                "rfq_responses",
                "vendor_id",
                ["rfq_id" => $response['rfq_id'], "response_id !=" => $response_id]
            );

            foreach ($other_vendors as $vendor) {
                $DB->INSERT("notifications", [
                    "user_id" => $vendor['vendor_id'],
                    "message" => "Your response to RFQ #{$response['rfq_id']} for {$rfq['product_name']} was not selected.",
                    "action" => "RFQResponse",
                    "status" => "Unread",
                    "created_at" => DATE_TIME
                ]);
            }
        }

        // Create contract only for accepted responses
        if ($status === 'Accepted') {
            $contractId = GENERATE_ID('CON-', 6);
            $expiration_date = date('Y-m-d', strtotime('+1 year')); // Default 1 year expiration

            $contractData = [
                'contract_id' => $contractId,
                'rfq_id' => $response['rfq_id'],
                'vendor_id' => $response['vendor_id'],
                'contract_file' => '',
                'status' => 'Pending',
                'renewal_status' => 'Pending',
                'expiration_date' => $expiration_date,
                'created_by' => AUTH_USER_ID
            ];

            $insertContract = $DB->INSERT('contracts', $contractData);
            if (!$insertContract['success']) {
                throw new Exception("Failed to create contract");
            }

            // Notify vendor about contract creation
            $DB->INSERT('notifications', [
                'user_id' => $response['vendor_id'],
                'message' => "A contract has been created for your accepted RFQ response. Please review and approve.",
                'action' => "/vendor-contract",
                'status' => 'Unread',
                'created_at' => DATE_TIME
            ]);
        }

        // Common notifications
        $vendor_message = ($status === 'Accepted')
            ? "Your response to RFQ #{$response['rfq_id']} for {$rfq['product_name']} has been accepted."
            : "Your response to RFQ #{$response['rfq_id']} for {$rfq['product_name']} has been rejected.";

        $DB->INSERT("notifications", [
            "user_id" => $response['vendor_id'],
            "message" => $vendor_message,
            "action" => "RFQResponse",
            "status" => "Unread",
            "created_at" => DATE_TIME
        ]);

        $action_user_message = ($status === 'Accepted')
            ? "You have accepted the response from vendor #{$response['vendor_id']} for RFQ #{$response['rfq_id']} ({$rfq['product_name']})."
            : "You have rejected the response from vendor #{$response['vendor_id']} for RFQ #{$response['rfq_id']} ({$rfq['product_name']}).";

        $DB->INSERT("notifications", [
            "user_id" => AUTH_USER_ID,
            "message" => $action_user_message,
            "action" => "RFQResponse",
            "status" => "Unread",
            "created_at" => DATE_TIME
        ]);

        toast("success", "RFQ response " . strtolower($status) . " successfully");
        die(refresh(2000));
    }

toast("error", "Invalid request parameters");
die(redirect('/request-for-qoute'));
