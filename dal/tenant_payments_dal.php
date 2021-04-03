<!-- 
    Title:       tenant_payments_dal.php
    Application: RentalBuddy
    Purpose:     Handles the tenant payment-related data access code
    Author:      G. Blandford, Group 5, INFO-5139-01-21W
    Date:        April 2nd, 2021 (April 2nd, 2021) 
-->
<?php

// Load common
define('__ROOT__', dirname(__FILE__));
require_once("../pages/common.php");

// Get a tenant payment
function getTenantPayment() {

    // tenant payment id
    $tenant_payment_id = $_SESSION['tenant_payment_id'];

    // create database connection
    $db_conn = connectDB();

    // SQL query
    $querySQL = "select
            tp.tenant_payment_id
            , tp.payment_type_code
            , tp.description
            , tp.payment_date
            , tp.payment_due
            , tp.discount_coupon_code
            , tp.discount
            , tp.payment_amount
            , tp.card_holder
            , tp.card_number
            , tp.card_expiry
            , tp.card_CVV
            , tp.status_code

            , t.tenant_id
            , trim( concat(ifnull(salutations.description, ''), ' ', t.first_name, ' ', t.last_name ) ) as tenant_name

        from tenant_payments as tp
        inner join tenants t on t.tenant_id = tp.tenant_id

        where tp.tenant_payment_id = :tenant_payment_id";

    // assign value to :tenant_payment_id
    $data = array(":tenant_payment_id" => $tenant_payment_id);

    // prepare query
    $stmt = $db_conn->prepare($querySQL);

    // prepare error check
    if (!$stmt) {
        echo "<p>Error: " . $db_conn->errorCode() . "<br>Message: " . implode($db_conn->errorInfo()) . "</p><br>";
        exit(1);
    }

    // execute query in database
    $status = $stmt->execute($data);

    if ($status) { // no error

        if ($stmt->rowCount() > 0) { // Found

            // Store row in the session
            $_SESSION['rowdata'] = $stmt->fetch(PDO::FETCH_ASSOC);
        }
    } else {
        // execute error
        echo "<p>Error: " . $stmt->errorCode() . "<br>Message: " . implode($stmt->errorInfo()) . "</p><br>";

        // close database connection
        $db_conn = null;

        exit(1);
    }
    // close database connection
    $db_conn = null;
}

// Save a payment
function saveTenantPayment() {

    $tenant_payment_id = $_SESSION['tenant_payment_id'];
    $rowdata = $_SESSION['rowdata'];

    // create database connection
    $db_conn = connectDB();

    if ( isset($_SESSION['CURRENT_USER']) && !empty($_SESSION['CURRENT_USER'] ) ) {
        $session_user_id = $_SESSION['CURRENT_USER']['user_id'];
    } else {
        $session_user_id = "admin";
    }

    if ($tenant_payment_id == 0) {

        // Add
        $querySQL = "insert into tenant_payments (
                        , tenant_id
                        , payment_type_code
                        , description
                        , payment_date
                        , payment_due
                        , discount_coupon_code
                        , discount
                        , payment_amount
                        , card_holder
                        , card_number
                        , card_expiry
                        , card_CVV
                        , status_code
                        , last_updated_user_id
                ) values (
                        , :tenant_id
                        , :payment_type_code
                        , :description
                        , :payment_date
                        , :payment_due
                        , :discount_coupon_code
                        , :discount
                        , :payment_amount
                        , :card_holder
                        , :card_number
                        , :card_expiry
                        , :card_CVV
                        , :status_code
                        , :session_user_id
                )";

        // assign data values
        $data = array(  ":tenant_id" => $rowdata['tenant_id'],
                        ":payment_type_code" => $rowdata['payment_type_code'],
                        ":description" => $rowdata['description'],
                        ":payment_date" => $rowdata['payment_date'],
                        ":payment_due" => $rowdata['payment_due'],
                        ":discount_coupon_code" => $rowdata['discount_coupon_code'],
                        ":discount" => $rowdata['discount'],
                        ":payment_amount" => $rowdata['payment_amount'],
                        ":card_holder" => $rowdata['card_holder'],
                        ":card_number" => $rowdata['card_number'],
                        ":card_expiry" => $rowdata['card_expiry'],
                        ":card_CVV" => $rowdata['card_CVV'],
                        ":status_code" => $rowdata['status_code'],
                        ":session_user_id" => $session_user_id
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

            if ($status) { 

                // Should give the identity 
                $tenant_payment_id = $db_conn->lastInsertId(); // Get rental property_id

            } else {
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
        
    } else {

        // Update
        $querySQL = "update tenant_payments as tp

                set 
                    tp.payment_type_code         = :payment_type_code
                    , tp.description             = :description
                    , tp.payment_date            = :payment_date
                    , tp.discount_coupon_code    = :discount_coupon_code
                    , tp.discount                = :discount
                    , tp.payment_amount          = :payment_amount

                    , tp.card_holder             = :card_holder
                    , tp.card_number             = :card_number
                    , tp.card_expiry             = :card_expiry
                    , tp.card_CVV                = :card_CVV
                    , tp.status_code             = :status_code
                    , tp.last_updated            = now()
                    , tp.last_updated_user_id    = :session_user_id

            where tp.tenant_payment_id = :tenant_payment_id";

        // assign data values
        $data = array(  ":tenant_payment_id" => $rowdata['tenant_payment_id'],
                        ":tenant_id" => $rowdata['tenant_id'],
                        ":payment_type_code" => $rowdata['payment_type_code'],
                        ":description" => $rowdata['description'],
                        ":payment_date" => $rowdata['payment_date'],
                        ":payment_due" => $rowdata['payment_due'],
                        ":discount_coupon_code" => $rowdata['discount_coupon_code'],
                        ":discount" => $rowdata['discount'],
                        ":payment_amount" => $rowdata['payment_amount'],
                        ":card_holder" => $rowdata['card_holder'],
                        ":card_number" => $rowdata['card_number'],
                        ":card_expiry" => $rowdata['card_expiry'],
                        ":card_CVV" => $rowdata['card_CVV'],
                        ":status_code" => $rowdata['status_code'],
                        ":session_user_id" => $session_user_id
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
}

// Return the tenant name
function getTenantName($tenant_id) {

    if (empty($tenant_id)) {
        return "";
    }

   // create database connection
   $db_conn = connectDB();


    $querySQL = "select
          
            trim( concat(ifnull(salutations.description, ''), ' ', t.first_name, ' ', t.last_name ) ) as tenant_name

       from tenants t

       where l.tenant_id = :tenant_id";

   // assign value to :tenant_id
   $data = array(":tenant_id" => $tenant_id);

   // prepare query
   $stmt = $db_conn->prepare($querySQL);

   // prepare error check
   if (!$stmt) {
       echo "<p>Error: " . $db_conn->errorCode() . "<br>Message: " . implode($db_conn->errorInfo()) . "</p><br>";
       exit(1);
   }

   // execute query in database
   $status = $stmt->execute($data);
   if ($status) { // no error

       if ($stmt->rowCount() > 0) { // Found

           // Return name
           return$stmt->fetch(PDO::FETCH_ASSOC)['tenant_name'];
       }
   } else {
       // execute error
       echo "<p>Error: " . $stmt->errorCode() . "<br>Message: " . implode($stmt->errorInfo()) . "</p><br>";

       // close database connection
       $db_conn = null;
       exit(1);
   }
   // close database connection
   $db_conn = null;    
}
?>