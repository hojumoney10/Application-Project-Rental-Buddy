<?php
require_once("./pages/common.php");
/*require_once("./dal/notifications_dal.php");*/
date_default_timezone_set('America/Toronto');

/*
Title:       crontab.php
Application: RentalBuddy
Purpose:     Handles the check if rent payment day is coming up in 7 days
Author:      J. Foster, Group 5, INFO-5139-01-21W
Date:        April, 8th, 2021 (April 8th, 2021) 
*/

//Connect to database using PDO method
$payments = paymentDays();
$today = date("Y-m-24"); /* RESET TO d FOR REAL DATE */ 
$today = new DateTime($today);
$today = $today->format("Y-m-d");
$payDay = [];
$tenantIds = [];
$tenantIdDue = [];
$dates = [];

foreach($payments as $payDate) { 
    if($payDate[1] < 10) {
        $payDate[1] = "0" . $payDate[1];
    }
    $payDate[1] = date("Y-m-$payDate[1]", strtotime("+1 month"));
    $payDate[1] = new DateTime($payDate[1]);
    $payDate[1] = $payDate[1]->format("Y-m-d");
    array_push($tenantIds, $payDate[0]);
    array_push($payDay, $payDate[1]);
}

for($i = 0; $i < count($payDay); $i++) {
    $today = new DateTime($today);
    $payDay[$i] = new DateTime($payDay[$i]);

    $interval = date_diff($today,$payDay[$i]);
    $interval = $interval->format("%R%a");
    
    if($interval == "+7") {
        $today = $today->format("Y-m-d");
        $payDay[$i] = $payDay[$i]->format("Y-m-d");

        array_push($tenantIdDue, $tenantIds[$i]);
        array_push($dates, $payDay[$i]);
    }
}

for($i = 0; $i < count($tenantIdDue); $i++) {
    $recipient = getUserId($tenantIdDue[$i]);
    createNotification(0, "landlord", $recipient, 'Rent due on ' . $dates[$i], " ", 0);
}

function paymentDays() {
    $db_conn = connectDB();
    $results = [];
    
    $stmt = $db_conn->prepare("SELECT tenant_id, payment_day, base_rent_amount, parking_amount, other_amount, start_date, end_date FROM leases WHERE status_code='active';");
    
    try {
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $tmp = [
                $row['tenant_id'],
                $row['payment_day'],
                $row['base_rent_amount'],
                $row['parking_amount'],
                $row['other_amount'],
                $row['start_date'],
                $row['end_date']
            ];
            array_push($results, $tmp);
        }       
        return $results;
    } catch (Exception $e) {
        $db_conn->rollback();
        echo $e->getMessage();
    }
}

function getUserId($tenant) {
    $db_conn = connectDB();

    $stmt = $db_conn->prepare("SELECT user_id FROM users 
    WHERE tenant_id='".$tenant."' ");
    
    try {
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $tmp = [
                $row['user_id']
            ];
        }       
        return $tmp;
    } catch (Exception $e) {
        $db_conn->rollback();
        echo $e->getMessage();
    }
}

function createNotification(
    $parent_notification_id = 0 // Future use
    , $sender_user_id = ""      // leave blank usually
    , $recipient_user_id        // 'to'
    , $details                  // notification details
    , $entity_type = ""         // Future use: specify entity if linked to an object
    , $entity_type_id = 0       //              id of the entity object
) {
    $db_conn = connectDB();
    // Add
    $querySQL = "insert into notifications (
        parent_notification_id
        , sender_user_id
        , recipient_user_id
        , details
        , entity_type
        , entity_type_id
        , sent_datetime
        , notification_status
    ) values (
        :parent_notification_id
        , :sender_user_id
        , :recipient_user_id
        , :details
        , :entity_type
        , :entity_type_id
        , now()
        , :notification_status
    )";
    
    // assign data values
    $data = array(
        ":parent_notification_id" => $parent_notification_id,
        ":sender_user_id" => $sender_user_id,
        ":recipient_user_id" => implode($recipient_user_id),
        ":details" => $details,
        ":entity_type" => $entity_type,
        ":entity_type_id" => $entity_type_id,
        ":notification_status" => 'unread'
    );
    
    // Transaction Start
    $db_conn->beginTransaction();
    
    // prepare query
    $stmt = $db_conn->prepare($querySQL);
    
    // prepare error check
    if (!$stmt) {

        echo "<p>Error: " . $db_conn->errorCode() . "<br>Message: " . implode($db_conn->errorInfo()) . "</p><br>";
        $db_conn->rollback(); // Transaction Rollback
        exit(1);
    }
    
    // execute query in database
    $status = $stmt->execute($data);
    
    if (!$status) {
        // execute error
        echo "<p>Error: " . $stmt->errorCode() . "<br>Message: " . implode($stmt->errorInfo()) . "</p><br>";
        $db_conn->rollback(); // Transaction Rollback

        // close database connection
        $db_conn = null;
        exit(1);
    }
    
    // Transaction Commit
    $db_conn->commit();
    
    // close database connection
    $db_conn = null;
}
?>