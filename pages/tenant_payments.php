<?php
session_start();
include_once("./check_session.php");

// session_unset();
$_SESSION['PAGE'] = "tenant_payments";
if (!isset($_SESSION['PAGEMODE'])) {
    $_SESSION['PAGEMODE'] = "LIST";
    $_SESSION['PAGENUM'] = 0;
}

?>
<!DOCTYPE html>
<html>

<head>
    <!-- 
        Title:       tenant_payments.php
        Application: RentalBuddy
        Purpose:     Handles tenant_payments
        Author:      G. Blandford,  Group 5, INFO-5139-01-21W
        Date:        April 4th, 2021 (April 4th, 2021)
    -->

    <title>RentalBuddy - Tenant Payments</title>

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="RentalBuddy Tenant Payments">
    <meta name="author" content="Graham Blandford">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../vendor/twbs/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Metrophobic&display=swap" rel="stylesheet">

    <!-- jQuery AJAX -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

    <!-- Use internal styles for now -->
    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }

        .backgroundColor {
            background: lightgray;
        }

        .fontColor {
            color: darkblue;
        }

        body {
            font-family: 'Metrophobic', sans-serif;
            font-size: 16px;
        }

        .container-crud {
            margin-left: 10px;
        }

        .btn-crud {
            min-width: 100px;
        }

        .btn-crud img {
            color: white;
        }

        .form-inline {
            display: inline-block;
        }

        .input-group {
            margin-bottom: 10px !important;
        }

        .form-group {
            margin-bottom: 10px !important;
        }

        .form-check-inline {
            margin: 5px 15px;
        }

        .form-check-input-payment {
            margin: 10px 0 !important;
        }

        label {
            width: 180px;
            justify-content: left !important;
        }

        .label-checkbox {
            width: 180px !important;
        }

        input[type=text] {
            width: 280px;

        }

        input-group select  {
            min-width: 285px;
        }

        fieldset {
            padding: 10px;
            border-radius: 10px;
        }

        legend {
            padding-top: 5px;
            border-radius: 5px;
            border: 5px;
            margin-bottom: 20px;
            text-align: center;
            height: 50px;
            vertical-align: middle !important;
        }


        #fieldset-card {
            border: 1px solid lightgray;
            margin: 20px 2px;
        }
        
    </style>

    <!-- Custom styles for this template -->
    <link href="../css/starter-template.css" rel="stylesheet">

    <?php
    // Load common
    define('__ROOT__', dirname(__FILE__));
    require_once(__ROOT__ . "/common.php");

    // Auto Mock Generator and symfony
    require_once '../vendor/autoload.php';
    ?>

</head>

<body>
    <?php

    // navigation & search bars
    require_once("./navigationMenu.php");
    require_once("./search-bar.php");
    require_once("./crud-buttons.php");

    // data access layer
    require_once("../dal/codes_dal.php");
    require_once("../dal/tenants_dal.php");
    require_once("../dal/tenant_payments_dal.php");

    // Check POST ACTIONS first
    if (isset($_POST['btn-add']) && ($_POST['btn-add'] == "Add")) { // Add clicked
        $_SESSION['PAGEMODE'] = "ADD";
        $_SESSION['PAGENUM'] = 0;
    } else if (isset($_POST['btn-edit']) && ($_POST['btn-edit'] == "Edit")) { // Edit clicked
        $_SESSION['PAGEMODE'] = "EDIT";
        $_SESSION['PAGENUM'] = 0;
    } else if (isset($_POST['btn-delete']) && ($_POST['btn-delete'] == "Delete")) { //
        $_SESSION['mode'] = "DELETE";
    } else if (isset($_POST['btn-view']) && ($_POST['btn-view'] == "View")) {
        $_SESSION['PAGEMODE'] = "VIEW";
        $_SESSION['PAGENUM'] = 0;
    } else if (isset($_POST['btn-cancel']) && ($_POST['btn-cancel'] == "Cancel")) {
        $_SESSION['PAGENUM'] = 0;
        $_SESSION['PAGEMODE'] = "LIST";
    } else if (isset($_POST['btn-save']) && ($_POST['btn-save'] == "Save")) { // Save clicked
        // Just let it through
    } else {
        $_SESSION['PAGENUM'] = 0;
        $_SESSION['PAGEMODE'] = "LIST";
    }

    // Get the Tenant id
    $_SESSION['tenant_id'] = $_SESSION['CURRENT_USER']['tenant_id'];

    // var_dump( $_SERVER['REQUEST_METHOD'] );
    // dump( $_SESSION );
    // var_dump( $_POST );
    // var_dump( $_GET );

    // $_ POSTing or $_GETting?
    if ($_SERVER['REQUEST_METHOD'] == 'GET' || $_SESSION['PAGEMODE'] == "LIST") {

        // Display Payments
        formDisplayTenantPayments();
    } else if ($_SESSION['PAGEMODE'] == "ADD" || $_SESSION['PAGEMODE'] == "EDIT" || $_SESSION['PAGEMODE'] == "VIEW") {

        switch ($_SESSION['PAGENUM']) {

            case 0: // Show Form
                $_SESSION['PAGENUM'] = 1; // Set to validate

                // ADD record
                if ($_SESSION['PAGEMODE'] == "ADD") {

                    // Empty record
                    $_SESSION['tenant_payment_id'] = 0;
                    $_SESSION['rowdata'] = array();

                    // Show Tenant Payment page
                    formTenantPayment();

                    // EDIT RECORD
                } else if (isset($_POST['selected']) && strlen($_POST['selected'][0] > 0)) {

                    // Get Selected Tenant Payment 
                    $_SESSION['tenant_payment_id'] = $_POST['selected'][0];
                    
                    // dump( $_SESSION );
                    // Get Tenant Payment data
                    getTenantPayment();

                    // Show Payment
                    formTenantPayment();

                    // LIST RECORDS
                } else {

                    formTenantPayment();
                }
                break;

            case 1: // Save
                if ((isset($_POST['btn-save'])) && ($_POST['btn-save'] == "Save")) {

                    // Validate
                    $err_msgs = validateTenantPayment();

                    if (count($err_msgs) > 0) {

                        displayErrors($err_msgs);
                        formTenantPayment();
                    } else {

                        // Save to database                     
                        saveTenantPayment();
                        $_SESSION['PAGENUM'] = 0;

                        // Clear row
                        unset($_SESSION['rowdata']);

                        $_SESSION['PAGEMODE'] = "LIST";
                        formDisplayTenantPayments();
                    }
                } else if ((isset($_POST['btn-cancel'])) && ($_POST['btn-cancel'] == "Cancel" || $_POST['btn-cancel'] == "OK")) {

                    $_SESSION['PAGEMODE'] = "LIST";
                    $_SESSION['PAGENUM'] = 0;

                    // Clear current data
                    unset($_SESSION['tenant_property_id']);
                    unset($_SESSION['rowdata']);

                    formDisplayTenantPayments();
                }
                
                break;
            default:
        }
    } else if ($_SESSION['PAGEMODE'] == "LIST") {

        formDisplayTenantPayments();
    }

    // We can do anything here AFTER the form has loaded
    ?>

    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="../vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <link href="../node_modules/bootstrap-icons/font//bootstrap-icons.css" rel="stylesheet">
</body>

</html>

<?php

function validateTenantPayment()
{
    $rowdata = $_SESSION['rowdata'];

    $rowdata['tenant_payment_id'] = $_POST['tenant-payment-id'];

    $err_msgs = [];

    // landlord must have been selected
    if (!isset($rowdata['landlord_id'])) {
        $err_msgs[] = "A landlord must be selected";
    } 

    // listing reference
    if (!isset($_POST['listing-reference'])) {
        $err_msgs[] = "A listing reference is required";
    } else {
        $rowdata['listing_reference'] = $_POST['listing-reference'];
        if (strlen($rowdata['listing_reference']) == 0) {
            $err_msgs[] = "An listing reference is required";
        } else if (strlen($rowdata['listing_reference']) > 20) {
            $err_msgs[] = "The listing reference exceeds 20 characters";
        }
    }
    
    // address 1
    if (!isset($_POST['address-1'])) {
        $err_msgs[] = "An address is required";
    } else {
        $rowdata['address_1'] = $_POST['address-1'];
        if (strlen($rowdata['address_1']) == 0) {
            $err_msgs[] = "An address is required";
        } else if (strlen($rowdata['address_1']) > 50) {
            $err_msgs[] = "The address exceeds 50 characters";
        }
    }

    // address 2
    if (isset($_POST['address-2'])) {
        $rowdata['address_2'] = $_POST['address-2'];
        if (strlen($rowdata['address_2']) > 50) {
            $err_msgs[] = "The address exceeds 50 characters";
        }
    }

    // city
    if (!isset($_POST['city'])) {
        $err_msgs[] = "The city is required";
    } else {
        $rowdata['city'] = $_POST['city'];
        if (strlen($rowdata['city']) == 0) {
            $err_msgs[] = "The city is required";
        } else if (strlen($rowdata['city']) > 50) {
            $err_msgs[] = "The city exceeds 50 characters";
        }
    }

    // province code
    if (!isset($_POST['province'])) {
        $err_msgs[] = "A province is required";
    } else {
        $rowdata['province_code'] = $_POST['province'];
        if (strlen($rowdata['province_code']) == 0) {
            $err_msgs[] = "A province is required";
        } else if (strlen($rowdata['province_code']) > 2) {
            $err_msgs[] = "The province exceeds 2 characters";
        }
    }

    // postal code - use REGEX
    if (!isset($_POST['postal-code'])) {
        $err_msgs[] = "A postal code is required";
    } else {
        $rowdata['postal_code'] = $_POST['postal-code'];
        if (strlen($rowdata['postal_code']) == 0) {
            $err_msgs[] = "A postal code is required";
        } else if (!preg_match('/^([a-zA-Z]\d[a-zA-Z])\ {0,1}(\d[a-zA-Z]\d)$/', trim($rowdata['postal_code']))) {
            $err_msgs[] = "The postal code is not valid";
        }
    }

    // latitude
    if (isset($_POST['latitude'])) {
        $rowdata['latitude'] = $_POST['latitude'];
        if (strlen($rowdata['latitude']) > 20) {
            $err_msgs[] = "The latitude exceeds 20 characters";
        }
    }

    // longitude
    if (isset($_POST['longitude'])) {
        $rowdata['longitude'] = $_POST['longitude'];
        if (strlen($rowdata['longitude']) > 20 ) {
            $err_msgs[] = "The longitude exceeds 20 characters";
        }
    }

    // property type code
    if (!isset($_POST['property-type-code'])) {
        $err_msgs[] = "A property type is required";
    } else {
        $rowdata['property_type_code'] = $_POST['property-type-code'];
        if (strlen($rowdata['property_type_code']) == 0) {
            $err_msgs[] = "A property type is required";
        } else if (strlen($rowdata['property_type_code']) > 20) {
            $err_msgs[] = "The property type exceeds 20 characters";
        }
    }

    // number bedrooms
    if (!isset($_POST['number-bedrooms'])) {
        $err_msgs[] = "The number of bedrooms is required";
    } else {
        $rowdata['number_bedrooms'] = $_POST['number-bedrooms'];
        if ($rowdata['number_bedrooms'] < 1 || $rowdata['number_bedrooms'] > 9 ) {
            $err_msgs[] = "The number of bedrooms must be between 1 and 9";
        } 
    }    

    // parking space type code
    if (!isset($_POST['parking-type-code'])) {
        $err_msgs[] = "A parking type is required";
    } else {
        $rowdata['parking_space_type_code'] = $_POST['parking-type-code'];
        if (strlen($rowdata['parking_space_type_code']) == 0) {
            $err_msgs[] = "A parking type is required";
        } else if (strlen($rowdata['parking_space_type_code']) > 20) {
            $err_msgs[] = "The parking type exceeds 20 characters";
        }
    }

    // number parking spaces
    if (!isset($_POST['number-parking-spaces'])) {
        $err_msgs[] = "The number of parking spaces is required";
    } else {
        $rowdata['number_parking_spaces'] = $_POST['number-parking-spaces'];
        if ($rowdata['number_parking_spaces'] < 0 || $rowdata['number_parking_spaces'] > 99 ) {
            $err_msgs[] = "The number of parking spaces must be between 0 and 99";
        } 
    }    

    // rental duration type
    if (!isset($_POST['rental-duration-code'])) {
        $err_msgs[] = "A rental period is required";
    } else {
        $rowdata['rental_duration_code'] = $_POST['rental-duration-code'];
        if (strlen($rowdata['rental_duration_code']) == 0) {
            $err_msgs[] = "A rental period is required";
        } else if (strlen($rowdata['rental_duration_code']) > 20) {
            $err_msgs[] = "The rental period exceeds 20 characters";
        }
    }

    // smoking
    $rowdata['smoking_allowed'] = (int)isset($_POST['smoking-allowed']);

    // insurance
    $rowdata['insurance_required'] = (int)isset($_POST['insurance-required']);

    // status code
    if (!isset($_POST['status-code'])) {
        $err_msgs[] = "A status is required";
    } else {
        $rowdata['status_code'] = $_POST['status-code'];
        if (strlen($rowdata['status_code']) == 0) {
            $err_msgs[] = "A status is required";
        } else if (strlen($rowdata['status_code']) > 10) {
            $err_msgs[] = "The status exceeds 10 characters";
        }
    }

    $_SESSION['rowdata'] = $rowdata;
    return $err_msgs;
}

function formDisplayTenantPayments() {

    $fvalue = "";
    if (isset($_POST['btn-search']) && isset($_POST['text-search'])) {
        $_SESSION['text-search'] = trim($_POST['text-search']);
        $fvalue = $_SESSION['text-search'];
    } else if (isset($_POST['btn-search-clear'])) {
        $_SESSION['text-search'] = "";
        $fvalue = $_SESSION['text-search'];
    } else if (isset($_SESSION['text-search'])) {
        $fvalue = $_SESSION['text-search'];
    }
?>
    <form method="POST">
        <?php

        // Get Data
        getTenantPayments($_SESSION['tenant_id'] );

        // Search Bar
        getSearch($fvalue);

        // Get Standard CRUD buttons
        getCRUDButtons();
        ?>

    </form>
    </div>
<?php }

function formTenantPayment($showmodal = 0)
{
    // Get the data
    $row = $_SESSION['rowdata'];

// dump($$row);

?>
    <div class="container-fluid">

        <form class="form form-inline" method="POST" style="padding-right: 30px;">
            <fieldset class="bg-light">
                <legend class="text-light bg-dark">
                    <?php
                    switch ($_SESSION['PAGEMODE']) {
                        case "VIEW":
                            echo "View ";
                            break;
                        case "EDIT":
                            echo "Edit ";
                            break;
                        default:
                            break;
                    }
                    ?>Tenant Payment</legend>

                <!-- tenant_id & name-->
                <div class="input-group">
                    <label for="tenant-id">Tenant</label>
                    <input type="text" size="10" maxlength="10" class="form-control" style="max-width: 100px" id="tenant-id" name="tenant-id" aria-describedby="tenant-id-help" placeholder="" value="<?php echo $row['tenant_id']; ?>" readonly>
                    <small id="tenant-id-help" class="form-text text-muted"></small>

                    <input type="text" size="30" maxlength="50" 
                            class="form-control" 
                            id="tenant-name" name="tenant-name" 
                            value="<?php 
                                        echo getTenantName($row['tenant_id']); ?>"
                            readonly
                            required
                            immediate="false">
                </div>

                <!-- description -->
                <div class="input-group">
                    <label for="description">Description</label>
                    <input type="text" size="30" maxlength="30" style="max-width: 50%;" class="form-control" id="description" name="description" aria-describedby="description-help" placeholder="Enter description" required value="<?php echo $row['description']; ?>"<?php echo ($_SESSION['PAGEMODE'] == 'VIEW') ? " readonly" : "" ?>>
                    <small id="description-help" class="form-text text-muted"></small>
                </div>

                <!-- payment type -->
                <div class="input-group">
                    <label for="payment-type-code">Payment Type</label>
                    <select class="selectpicker form-control" style="max-width: 220px;" id="payment-type-code" name="payment-type-code" aria-describedby="payment-type-code-help" placeholder="Enter payment type" required<?php echo ($_SESSION['PAGEMODE'] == 'VIEW') ? " readonly" : "" ?>>
                        <?php
                        getCodes('payment_type', $row['payment_type_code']);
                        ?>
                    </select>
                    <small id="payment-type-code-help" class="form-text text-muted"></small>
                </div>

                <!-- Contact prefs -->
                <label for="fieldset-card">Card Details</label>

                <fieldset id="fieldset-card" class="bg-light">

                    <!-- cardholder name -->
                    <div class="input-group">
                        <label for="cardholder-name">Name</label>
                        <input type="text" size="30" maxlength="50" class="form-control" id="cardholder-name" name="cardholder-name" aria-describedby="cardholder-name-help" placeholder="Enter cardholder name" value="<?php echo $row['cardholder_name']; ?>" <?php echo ($_SESSION['PAGEMODE'] == 'VIEW') ? " readonly" : "" ?>>
                        <small id="cardholder-name-help" class="form-text text-muted"></small>
                    </div>

                    <!-- card number -->
                    <div class="input-group">
                        <label for="card-number">Number</label>
                        <input type="text" size="30" maxlength="50" class="form-control" id="card-number" name="card-number" aria-describedby="card-number-help" placeholder="Enter card number" value="<?php echo $row['card_number']; ?>" <?php echo ($_SESSION['PAGEMODE'] == 'VIEW') ? " readonly" : "" ?>>
                        <small id="card-number-help" class="form-text text-muted"></small>
                    </div>

                    <!-- expiry & cvv -->
                    <div class="input-group">
                        <label for="card-expiry">Expiry/CVV</label>
                        <input type="text" size="15" maxlength="15" style="max-width: 50%;" class="form-control" id="card-expiry" name="card-expiry" aria-describedby="card-expiry-help" placeholder="Enter expiry date" value="<?php echo $row['card_expiry']; ?>"<?php echo ($_SESSION['PAGEMODE'] == 'VIEW') ? " readonly" : "" ?>>
                        <small id="card-expiry-help" class="form-text text-muted"></small>

                        <input type="text" size="15" maxlength="15" style="max-width: 50%;" class="form-control" id="card-cvv" name="card-cvv" aria-describedby="card-cvv-help" placeholder="Enter CVV" value="<?php echo $row['card_CVV']; ?>"<?php echo ($_SESSION['PAGEMODE'] == 'VIEW') ? " readonly" : "" ?>>
                        <small id="longitude-help" class="form-text text-muted"></small>
                    </div>

                </fieldset>                    

                <!-- payment due -->
                <div class="input-group">
                    <label for="payment-due">Payment Due</label>
                    <input type="text" size="15" maxlength="15" style="max-width: 20%;" class="form-control" id="payment-due" name="payment-due" aria-describedby="payment-due-help" placeholder="Enter payment due" value="<?php echo $row['payment_due']; ?>" required readonly>
                    <small id="payment-due-help" class="form-text text-muted"></small>
                </div>

                <!-- discount code & value -->
                <div class="input-group">
                    <label for="discount-coupon-code">Discount</label>
                    <select class="selectpicker form-control" style="max-width: 220px;" id=discount-coupon-code" name="discount-coupon-code" aria-describedby="discount-coupon-code-help" <?php echo ($_SESSION['PAGEMODE'] == 'VIEW') ? " readonly" : "" ?>>
                        <option disabled selected></option>
                        <?php
                        getCodes('discount_code', $row['discount_coupon_code']);
                        ?>
                    </select>
                    <small id="discount-coupon-code-help" class="form-text text-muted"></small>

                    <input type="text" style="max-width: 100px;" class="form-control" id="discount" name="discount" aria-describedby="discount-help" placeholder="Discount" value="<?php echo $row['discount']; ?>" readonly>
                    <small id="discount-help" class="form-text text-muted"></small>
                </div>

                <!-- payment amount -->
                <div class="input-group">
                    <label for="payment-amount">Payment Amount</label>
                    <input type="text" size="15" maxlength="15" style="max-width: 20%;" class="form-control" id="payment-amount" name="payment-amount" aria-describedby="payment-amount-help" placeholder="Enter payment amount" value="<?php echo $row['payment_amount']; ?>" required readonly>
                    <small id="payment-amount-help" class="form-text text-muted"></small>
                </div>

                <!--status  -->
                <div class="input-group">
                    <label for="status-code">Status</label>
                    <select class="selectpicker form-control" style="max-width: 100px" id="status-code" name="status-code" aria-describedby="status-code-help" placeholder="Enter status" required<?php echo ($_SESSION['PAGEMODE'] == 'VIEW') ? " readonly" : "" ?>>
                        <?php
                        getCodes('payment_status', $row['status_code']);
                        ?>
                    </select>
                    <small id="status-code-help" class="form-text text-muted"></small>
                </div>

                <table>
                    <tr>
                        <?php
                        if ($_SESSION['PAGEMODE'] == 'EDIT' || $_SESSION['PAGEMODE'] == 'ADD') { ?>
                            <td><input type="submit" class="btn btn-success btn-crud" name="btn-save" value="Save"></td>
                        <?php            }
                        ?>
                        <td><input type="submit" form="form-cancel" class="btn <?php echo ($_SESSION['PAGEMODE'] == 'EDIT' || $_SESSION['PAGEMODE'] == 'ADD') ? 'btn-secondary' : 'btn-primary'; ?> btn-crud" name="btn-cancel" value="<?php echo ($_SESSION['PAGEMODE'] == 'EDIT' || $_SESSION['PAGEMODE'] == 'ADD') ? 'Cancel' : 'OK'; ?>"></td>
                    </tr>
                </table>
            </fieldset>
        </form>
        <!-- empty form for cancel button -->
        <form id="form-cancel" hidden>
            <form>
    </div>
<?php
}
?>